<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Livewire\WithFileUploads;

class WireForm extends Component
{
    use WithFileUploads;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Livewire\Upload;
    use \Jiny\Table\Http\Livewire\Tabbar;

    use \Jiny\Table\Http\Livewire\Trait\Request;

    public $actions;
    public $table;
    public $forms=[];
    private $controller;

    public $http_referer;
    public $status;

    public $message;
    public $error = false;

    public function closeError()
    {
        $this->error = false;
        $this->message = null;
    }

    public function mount()
    {
        $this->permitCheck();
        if(isset($_SERVER['HTTP_REFERER'])) {
            $this->http_referer = $_SERVER['HTTP_REFERER'];
        }
    }

    public function render()
    {
        // 생성 및 수정동작 지정
        if (isset($this->actions['id'])) {
            $this->edit($this->actions['id']);
        } else {
            $this->create();
        }

        // 사용자 레이아웃 지정
        if(isset($this->actions['view_main_layout'])) {
            $viewFile = $this->actions['view_main_layout'];
        } else {
            $viewFile = "jinytable::livewire.form";
        }

        return view($viewFile);
    }





    /** ----- ----- ----- ----- -----
     *  신규 데이터 삽입
     */

    public function create($value=null)
    {
        // 삽입 권한이 있는지 확인
        if($this->permit['create']) {
            unset($this->actions['id']);

            // 후킹:: 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCreating")) {
                $form = $controller->hookCreating($this, $value);
                if($form && is_array($form)) {
                    $this->forms = $form;
                } else if($form === false) {
                    // 오류처리 팝업창
                    $this->error = true;
                    return false;
                }
            }

        } else {
            // 권한 없음 팝업을 활성화 합니다.
            $this->popupPermitOpen();
        }
    }


    public function clear()
    {
        $this->forms = [];
    }

    public function store()
    {
        $this->message = null;

        if($this->permit['create']) {

            // 1.유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
            }

            // 2. 시간정보 생성
            $this->forms['created_at'] = date("Y-m-d H:i:s");
            $this->forms['updated_at'] = date("Y-m-d H:i:s");

            // 3. 파일 업로드 체크
            $this->fileUpload();

            // 4. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookStoring")) {
                $form = $controller->hookStoring($this, $this->forms);
                if($form === false) {
                    // 오류처리 팝업창
                    $this->error = true;
                    return false;
                }
            } else {
                $form = $this->forms;
            }

            // 5. 데이터 삽입
            if($form) {
                $id = DB::table($this->actions['table']['name'])->insertGetId($form);
                $form['id'] = $id;

                // 6. 컨트롤러 메서드 호출
                if ($controller = $this->isHook("hookStored")) {
                    $result = $controller->hookStored($this, $form);
                    if($result === false) {
                        // 오류처리 팝업창
                        $this->error = true;
                        return false;
                    }
                }
            }

            // 입력데이터 초기화
            $this->clear();

        } else {
            $this->popupPermitOpen();
        }
    }





    public function submit()
    {
        if (isset($this->actions['id'])) {
            $this->update();
            $this->status = "update";
        } else {
            $this->store();
            $this->status = "store";
        }

        $this->goToIndex();
    }


    /*
    public function store()
    {
        if($this->permit['create']) {

            // 1.유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
            }

            // 2. 시간정보 생성
            $this->forms['created_at'] = date("Y-m-d H:i:s");
            $this->forms['updated_at'] = date("Y-m-d H:i:s");

            // 3. 파일 업로드 체크
            $this->fileUpload();

            // 4. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookStoring")) {
                $form = $controller->hookStoring($this, $this->forms);
            } else {
                $form = $this->forms;
            }

            // 5. 데이터 삽입
            if($form) {
                $id = DB::table($this->actions['table']['name'])->insertGetId($form);
                $this->forms['id'] = $id;

                // 6. 컨트롤러 메서드 호출
                if ($controller = $this->isHook("hookStored")) {
                    $controller->hookStored($this, $this->forms);
                }
            }

            // 입력데이터 초기화
            $this->clear();

        } else {
            $this->popupPermitOpen();
        }

    }
    */

    /** ----- ----- ----- ----- -----
     *  데이터 수정
     */

    public function edit($id)
    {
        if($this->permit['update']) {

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
                $row = DB::table($this->actions['table']['name'])->find($this->actions['id']);
                $this->setForm($row);
            }

            // 후크: 데이터 조회후 동작
            if ($controller = $this->isHook("hookEdited")) {
                $this->forms = $controller->hookEdited($this, $this->forms);
            }

        } else {
            $this->popupPermitOpen();
            return false;
        }
    }

    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->forms[$key] = $value;
        }
    }




    public $old=[];
    public function getOld($key=null)
    {
        if ($key) {
            return $this->old[$key];
        }
        return $this->old;
    }

    public function update()
    {
        if($this->permitUpdate()) {
            // 데이터 수정 진행
            $this->updateProcess();

        } else {
            // 권한없음 팝업창 출력
            $this->popupPermitOpen();
        }
    }

    private function updateProcess()
    {
        // step1. 수정전, 원본 데이터 읽기
        if (isset($this->actions['id'])) {
            $origin = DB::table($this->actions['table']['name'])->find($this->actions['id']);
            foreach ($origin as $key => $value) {
                $this->old[$key] = $value;
            }
        }

        // step2. 유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
        }

        // step3. 컨트롤러 메서드 호출
        if ($controller = $this->isHook("hookUpdating")) {
            $form = $controller->hookUpdating($this, $this->forms, $this->old);
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
        if(isset($origin)) {
            $this->checkEditUploadFile($origin);
        }



        // step5. 데이터 수정
        if($this->forms) {
            //dd($this->forms);
            $this->forms['updated_at'] = date("Y-m-d H:i:s");

            if (isset($this->actions['id'])) {
                DB::table($this->actions['table']['name'])
                    ->where('id', $this->actions['id'])
                    ->update($this->forms);
            }
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
        //$this->clear();
        $this->forms = [];
    }







    /** ----- ----- ----- ----- -----
     *  데이터 삭제 동작
     *  삭제는 2단계로 동작합니다. 삭제 버튼을 클릭하면, 실제 동작 버튼이 활성화 됩니다.
     */
    public $confirm = false;
    public $delete_code;

    public function deleteConfirm()
    {
        // 삭제권환 체크
        if($this->permit['delete']) {
            $this->confirm = true;
        } else {
            $this->popupPermitOpen();
        }
    }

    // 실제 삭제 동작
    public function delete()
    {
        if($this->permit['delete']) {
            $row = DB::table($this->actions['table']['name'])->find($this->actions['id']);

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookDeleting")) {
                $row = $controller->hookDeleting($row);
            }

            // uploadfile 필드 조회
            $fields = DB::table('uploadfile')->where('table', $this->actions['table']['name'])->get();
            foreach($fields as $item) {
                $key = $item->field; // 업로드 필드명
                if (isset($row->$key)) {
                    Storage::delete($row->$key);
                }
            }

            // 데이터 삭제
            DB::table($this->actions['table']['name'])
                ->where('id', $this->actions['id'])
                ->delete();

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookDeleted")) {
                $row = $controller->hookDeleted($row);
            }

            // 입력데이터 초기화
            $this->clear();

            $this->goToIndex();

        } else {
            $this->popupPermitOpen();
        }
    }


    public $back=true;
    private function goToIndex()
    {
        if($this->http_referer) {
            return redirect()->to($this->http_referer);
        }
    }

}
