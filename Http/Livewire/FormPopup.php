<?php
namespace Jiny\Table\Http\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

/**
 * 폼 팝업창
 */
class FormPopup extends Component
{
    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Hook;
    use \Jiny\WireTable\Http\Trait\Permit;
    use \Jiny\WireTable\Http\Trait\UploadSlot;

    public $actions;
    public $admin_prefix;
    public $message;

    // 테이블
    public $tablename;

    // 화면
    public $viewFile, $viewTable, $viewList, $viewForm;

    public $popupForm = false;
    public $popupDelete = false;
    public $popupWindowWidth = "4xl";
    public $confirm = false;

    public $_id;
    public $forms=[];
    public $forms_old=[];

    public function mount()
    {
        // 테이블명 지정
        if(!$this->tablename) {
            if(isset($this->actions['table']['name'])) {
                $this->tablename = $this->actions['table']['name'];
            }
        }

        // admin 접속경로 prefix
        if(function_exists('admin_prefix')) {
            $this->admin_prefix = admin_prefix();
        } else {
            $this->admin_prefix = "admin";
        }

        $this->permitCheck();

        // 입력폼 뷰 파일 지정
        if($this->viewForm) {
            $this->actions['view']['form'] = $this->viewForm;
        }

    }


    /**
     * 목록 출력
     */
    public function render()
    {
        // 1. 데이터 테이블 체크
        if(!$this->tablename) {
            // 테이블명이 없는 경우
return <<<BLADE
    <div class="alert alert-danger">
        테이블명이 지정되어 있지 않습니다.
    </div>
BLADE;
        }

        $this->viewFile = "jiny-table"."::forms.popup.layout";
        return view($this->viewFile,[
        ]);
    }


    // public function updateForms($name, $value)
    // {
    //     // $this->forms[$name] = $value;

    //     // 이메일 필드가 변경되는 경우
    //     if($name == "email") {
    //         // dd($value);
    //         // 이메일 변경시 동작할 코드를 작성합니다.
    //         // $this->dispatch('email-changed', $value);
    //     }
    // }


    public function popupFormOpen()
    {
        $this->popupForm = true;
        $this->confirm = false;
    }

    public function popupFormClose()
    {
        $this->popupForm = false;
        $this->confirm = false;
    }

    private function formInitField()
    {
        $this->forms = [];
        return $this;
    }

    /**
     * 입력 데이터 취소 및 초기화
     */
    public function cancel()
    {
        $this->forms = [];
        //$this->forms_old = [];
        $this->popupForm = false;
        $this->popupDelete = false;
        $this->confirm = false;
    }


    #[On('createPopupForm')]
    public function create($value=null)
    {
        $this->message = null;

        // 신규 삽입을 위한 데이터 초기화
        $this->forms = [];

        // 삽입 권한이 있는지 확인
        if($this->permit['create']) {
            unset($this->actions['id']);

            // 후킹:: 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCreating")) {
                $forms = $this->controller->hookCreating($this, $value);
                if($forms) {
                    $this->forms = $forms;
                }
            }

            // 폼입력 팝업창 활성화
            $this->popupFormOpen();

        } else {
            // 권한 없음 팝업을 활성화 합니다.
            $this->popupPermitOpen();
        }
    }


    public $last_id;
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

            // 3. 파일 업로드 체크 Trait
            $this->fileUpload();


            // 4. 컨트롤러 메서드 호출
            // 신규 데이터 DB 삽입전에 호출되는 Hook
            if ($controller = $this->isHook("hookStoring")) {
                $_form = $this->controller->hookStoring($this, $this->forms);
                if(is_array($_form)) {
                    $form = $_form;
                } else {
                    // 훅 처리시 오류가 발생됨.
                    //$this->message = $_form;
                    return null;
                }

                //dd($_form);
            } else {
                $form = $this->forms;
            }

            // 5. 데이터 삽입
            if($form) {
                //dd($form);
                $id = DB::table($this->tablename)->insertGetId($form);
                $form['id'] = $id;
                $this->last_id = $id;

                // 6. 컨트롤러 메서드 호출
                if ($controller = $this->isHook("hookStored")) {
                    $controller->hookStored($this, $form);
                }
            }

            // 입력데이터 초기화
            $this->cancel();

            // 팝업창 닫기
            $this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            $this->dispatch('refeshTable');

        } else {
            $this->popupPermitOpen();
        }
    }


    #[On('editPopupForm')]
    public function edit($id)
    {
        $this->popupForm = true;
        $this->confirm = false;
        $this->message = null;

        if($this->permit['update']) {
            $this->popupFormOpen();

            if($id) {
                $this->actions['id'] = $id;
            }

            // 1. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEditing")) {
                $this->forms = $this->controller->hookEditing($this, $this->forms);
            }

            if (isset($this->actions['id'])) {
                $row = DB::table($this->tablename)->find($this->actions['id']);
                $this->setForm($row);
            }

            // 2. 수정 데이터를 읽어온후, 값을 처리해야 되는 경우
            if ($controller = $this->isHook("hookEdited")) {
                $this->forms = $this->controller->hookEdited($this, $this->forms, $this->forms);
            }

        } else {
            $this->popupPermitOpen();
        }
    }

    // Object를 Array로 변경합니다.
    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->forms[$key] = $value;
            // 데이터 변경여부를 체크하기 위해서 old 값 지정
            $this->forms_old[$key] = $value;
        }
    }

    public function resetForm($name=null)
    {
        if($name) {
            $this->forms[$name] = null;
        }
    }

    public function getOld($key=null)
    {
        if ($key) {
            return $this->forms_old[$key];
        }
        return $this->forms_old;
    }

    public function update()
    {
        if($this->permit['update']) {
            // step1. 수정전, 원본 데이터 읽기
            $origin = DB::table($this->tablename)->find($this->actions['id']);
            foreach ($origin as $key => $value) {
                $this->forms_old[$key] = $value;
            }

            // step2. 유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
            }

            // step3. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookUpdating")) {
                $_form = $this->controller->hookUpdating($this, $this->forms, $this->forms_old);
                if(is_array($_form)) {
                    $this->forms = $_form;
                } else {
                    // Hook에서 오류가 반환 되었습니다.
                    return null;
                }
            }


            // step4. 파일 업로드 체크 Trait
            $this->fileUpload();

            // step5. 데이터 수정
            if($this->forms) {
                $this->forms['updated_at'] = date("Y-m-d H:i:s");
                DB::table($this->tablename)
                    ->where('id', $this->actions['id'])
                    ->update($this->forms);
            }

            // step6. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookUpdated")) {
                $result = $this->controller->hookUpdated($this, $this->forms, $this->forms_old);
                //dd($result);
                if($result === false) {
                    return false;
                }
            }

            // 입력데이터 초기화
            $this->cancel();

            // Livewire Table을 갱신을 호출합니다.
            $this->dispatch('refeshTable');

            // 팝업창 닫기
            $this->popupFormClose();

        } else {
            $this->popupPermitOpen();
        }
    }

    /** ----- ----- ----- ----- -----
     *  데이터 삭제
     *  삭제는 2단계로 동작합니다. 삭제 버튼을 클릭하면, 실제 동작 버튼이 활성화 됩니다.
     */
    public function delete($id=null)
    {
        if($this->permit['delete']) {
            $this->popupDelete = true;
        }
    }

    public function deleteCancel()
    {
        $this->popupDelete = false;
    }

    public function deleteConfirm()
    {
        $this->popupDelete = false;

        if($this->permit['delete']) {
            $row = DB::table($this->tablename)->find($this->actions['id']);
            $form = [];
            foreach($row as $key => $value) {
                $form[$key] = $value;
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookDeleting")) {
                $row = $this->controller->hookDeleting($this, $form);
            }

            // 데이터 삭제
            DB::table($this->tablename)
                ->where('id', $this->actions['id'])
                ->delete();

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookDeleted")) {
                $row = $this->controller->hookDeleted($this, $form);
            }

            // 입력데이터 초기화
            $this->cancel();
            unset($this->actions['id']);

            // 팝업창 닫기
            $this->popupFormClose();
            $this->popupDelete = false;

        } else {
            $this->popupFormClose();
            $this->popupPermitOpen();
        }

    }

}
