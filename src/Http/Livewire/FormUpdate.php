<?php
/**
 * Livewirw FormUpdate를 처리합니다.
 */
namespace Jiny\Table\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait FormUpdate
{
    public $edit_id;
    public $old=[];

    /** ----- ----- ----- ----- -----
     *  데이터 수정
     */
    ## Popup창을 통한 Edit폼 출력
    public function popupEdit($id)
    {
        //dd("dfas");

        //권환체트
        if($this->permitUpdate()) {
            $this->popupFormOpen();
            $this->editProcess($id); // form 처리
        } else {
            // 권한없음 팝업창 출력
            $this->popupPermitOpen();
        }
    }

    ## 일반 Edit 폼 출력
    public function edit($id)
    {
        //권환체트
        if($this->permitUpdate()) {
            $this->editProcess($id); // form 처리
        } else {
            // 권한없음 팝업창 출력
            //dd("권환없음");
            $this->popupPermitOpen();
        }
    }

    ## 화면 수정폼 출력 process
    private function editProcess($id)
    {
        if($id) {
            $this->edit_id = $id;
            $this->actions['id'] = $id;
        }

        // 후크: 데이터 조회전 동작
        if ($controller = $this->isHook("hookEditing")) {
            $this->forms = $controller->hookEditing($this, $this->forms);
        }

        // 데이터를 읽어 form필드를
        if (isset($this->actions['id'])) {
            $row = DB::table($this->actions['table'])->find($this->actions['id']);
            $this->setForm($row);
        }

        // 후크: 데이터 조회후 동작
        if ($controller = $this->isHook("hookEdited")) {
            $this->forms = $controller->hookEdited($this, $this->forms);
        }


    }

    ## form 값을 설정합니다.
    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->forms[$key] = $value;
        }
    }

    ## 수정 이전값 확인
    public function getOld($key=null)
    {
        if ($key) {
            return $this->old[$key];
        }
        return $this->old;
    }

    ## 수정동작
    public function update()
    {
        if($this->permitUpdate()) {
            // 데이터 수정 진행
            $result = $this->updateProcess();
            if(is_null($result)) {
                return;
            }


            // 팝업창 닫기
            $this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');

        } else {
            // 권한없음 팝업창 출력
            $this->popupPermitOpen();
        }
    }

    private function updateProcess()
    {
        // step1. 수정전, 원본 데이터 읽기
        $origin = DB::table($this->actions['table'])->find($this->actions['id']);
        foreach ($origin as $key => $value) {
            $this->old[$key] = $value;
        }

        // step2. 유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
        }

        // step3. 컨트롤러 메서드 호출
        if ($controller = $this->isHook("hookUpdating")) {
            $form = $controller->hookUpdating($this, $this->forms, $this->old);
            if(is_null($form)) {
                // 후크에서 오류로 null을 반환하는 경우
                // 동작중단
                return null;
            } else
            if($form && is_array($form)) {
                $this->forms = $form;
            } else if($form === false) {
                // 오류처리 팝업창
                $this->error = true;
                return false;
            }
        }


        // step4. 파일 업로드 체크
        $this->fileUpload();
        $this->checkEditUploadFile($origin);


        // step5. DB 데이터 수정
        if($this->forms) {
            $this->forms['updated_at'] = date("Y-m-d H:i:s");
            DB::table($this->actions['table'])
                ->where('id', $this->actions['id'])
                ->update($this->forms);
        }

        // step6. 컨트롤러 메서드 호출
        if ($controller = $this->isHook("hookUpdated")) {
            $form = $controller->hookUpdated($this, $this->forms, $this->old);
            if($form && is_array($form)) {
                $this->forms = $form;
            } else if($form === false) {
                // 오류처리 팝업창
                $this->error = true;
                return false;
            }
        }

        // 입력데이터 초기화
        $this->cancel();

        return true;
    }

}
