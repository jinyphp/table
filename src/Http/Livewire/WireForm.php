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
    public function mount()
    {
        $this->permitCheck();
        if(isset($_SERVER['HTTP_REFERER'])) {
            $this->http_referer = $_SERVER['HTTP_REFERER'];
        }
    }

    public function render()
    {
        if($this->status) {
            return <<<'blade'
            <div class="alert alert-success">
                processing...
            </div>
        blade;
        }

        if (isset($this->actions['id'])) {
            // 수정
            $id = $this->actions['id'];
            $this->edit($id);
        } else {
            // 생성
            $this->create();
        }

        return view("jinytable::livewire.form");
    }





    /** ----- ----- ----- ----- -----
     *  신규 데이터 삽입
     */

    public function create($value=null)
    {
        $this->forms = []; //초기화

        if($this->permit['create']) {
            unset($this->actions['id']);

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCreating")) {
                $controller->hookCreating($this, $value);
            }

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

    public function clear()
    {
        $this->forms = [];
    }

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
                $id = DB::table($this->actions['table'])->insertGetId($form);
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

    /** ----- ----- ----- ----- -----
     *  데이터 수정
     */

    public function edit($id)
    {
        if($this->permit['update']) {

            if($id) {
                $this->actions['id'] = $id;
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEditing")) {
                $this->forms = $controller->hookEditing($this, $this->forms);
            }

            if (isset($this->actions['id'])) {
                $row = DB::table($this->actions['table'])->find($this->actions['id']);
                if($row) {
                    $this->setForm($row);
                } else {
                    return false;
                    //dd("데이터를 읽을 수 없습니다. 삭제되었나요?");
                    //return "데이터를 읽을 수 없습니다. 삭제되었나요?";
                }

                // 컨트롤러 메서드 호출
                if ($controller = $this->isHook("hookEdited")) {
                    $this->forms = $controller->hookEdited($this, $this->forms);
                }

            }
            return true;

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

        if($this->permit['update']) {
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
                $this->forms = $controller->hookUpdating($this->forms);
            }

            // step4. 파일 업로드 체크
            $this->fileUpload();
            // uploadfile 필드 조회
            $fields = DB::table('uploadfile')->where('table', $this->actions['table'])->get();
            foreach($fields as $item) {
                $key = $item->field; // 업로드 필드명
                if($origin->$key != $this->forms[$key]) {
                    ## 이미지를 수정하는 경우, 기존 이미지는 삭제합니다.
                    Storage::delete($origin->$key);
                }
            }

            // step5. 데이터 수정
            if($this->forms) {
                DB::table($this->actions['table'])
                    ->where('id', $this->actions['id'])
                    ->update($this->forms);
            }

            // step6. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookUpdated")) {
                $this->forms = $controller->hookUpdated($this->forms);
            }

            // 입력데이터 초기화
            $this->clear();

        } else {

            $this->popupPermitOpen();
        }

    }



    /** ----- ----- ----- ----- -----
     *  데이터 삭제 동작
     *  삭제는 2단계로 동작합니다. 삭제 버튼을 클릭하면, 실제 동작 버튼이 활성화 됩니다.
     */
    public $confirm = false;

    public function deleteConfirm()
    {
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
            $row = DB::table($this->actions['table'])->find($this->actions['id']);

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookDeleting")) {
                $row = $controller->hookDeleting($row);
            }

            // uploadfile 필드 조회
            $fields = DB::table('uploadfile')->where('table', $this->actions['table'])->get();
            foreach($fields as $item) {
                $key = $item->field; // 업로드 필드명
                if (isset($row->$key)) {
                    Storage::delete($row->$key);
                }
            }

            // 데이터 삭제
            DB::table($this->actions['table'])
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
