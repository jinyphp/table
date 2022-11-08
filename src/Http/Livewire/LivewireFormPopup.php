<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;

class LivewireFormPopup extends Component
{
    use WithFileUploads;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Livewire\Upload;
    use \Jiny\Table\Http\Livewire\Tabbar;

    use \Jiny\Table\Http\Livewire\Trait\WirePopupDelete;

    /**
     * LivePopupForm with AlpineJS
     */
    public $actions;
    public $forms=[];
    public $mode;
    private $controller;

    public $message;



    public function mount()
    {
        $this->permitCheck();
    }

    public function render()
    {
      
        ## 팝업 레이아웃
        return view("jinytable::livewire.popup.form");
    }

    /** ----- ----- ----- ----- -----
     *  팝업창 관리
     */
    protected $listeners = [
        'popupFormOpen','popupFormClose',
        'create','popupFormCreate',
        'edit','popupEdit'
    ];
    public $popupForm = false;
    public function popupFormOpen()
    {
        $this->popupForm = true;
        $this->confirm = false;
    }

    public function popupFormClose()
    {
        $this->popupForm = false;
    }



    /** ----- ----- ----- ----- -----
     *  신규 데이터 삽입
     */

    public function popupFormCreate($value=null)
    {
        // create 메소드를 호출합니다.
        return $this->create($value);
    }

    private function formInitField()
    {
        $this->forms = [];
        return $this;
    }

    public function create($value=null)
    {
        // 신규 삽입을 위한 데이터 초기화
        $this->formInitField();

        $this->createRef($value);
    }

    public function createRef($value=null)
    {
        // 삽입 권한이 있는지 확인
        if($this->permit['create']) {
            unset($this->actions['id']);

            // 후킹:: 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCreating")) {
                $form = $controller->hookCreating($this, $value);
                if($form) {
                    $this->forms = $form;
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

            // 3. 파일 업로드 체크
            $this->fileUpload();

            // 4. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookStoring")) {
                $form = $controller->hookStoring($this, $this->forms);

                // hook 오류
                if($form === null) {
                    return;
                }
            } else {
                $form = $this->forms;
            }

            // 5. 데이터 삽입
            if($form) {
                //dd($form);
                $id = DB::table($this->actions['table'])->insertGetId($form);
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
            $this->emit('refeshTable');

        } else {
            $this->popupPermitOpen();
        }
    }


    /**
     * 입력 데이터 취소 및 초기화
     */
    public function cancel()
    {
        $this->forms = [];
    }


    /** ----- ----- ----- ----- -----
     *  데이터 수정
     */
    public function popupEdit($id)
    {
        //dd("edit");
        $this->edit($id);
    }

    public function edit($id)
    {
        if($this->permit['update']) {
            $this->popupFormOpen();

            if($id) {
                $this->actions['id'] = $id;
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEditing")) {
                $this->forms = $controller->hookEditing($this, $this->forms);

                // hook 오류
                if($this->forms === null) {
                    return;
                }
            }

            if (isset($this->actions['id'])) {
                $row = DB::table($this->actions['table'])->find($this->actions['id']);
                $this->setForm($row);
            }

            // checkbox 처리
            foreach($this->forms as $key => $value) {
                // 필드명이 _로 시작되는 경우 checkbox
                if($key[0] == '_') {
                    if($value == '0') $this->forms[$key] = null;
                }
            }
            

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEdited")) {
                $this->forms = $controller->hookEdited($this, $this->forms);
            }

        } else {
            $this->popupPermitOpen();
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
                $forms = $controller->hookUpdating($this, $this->forms, $this->old);
                if($forms === null) {
                    return;
                }
                $this->forms = $forms;
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

            // checkbox 처리
            foreach($this->forms as $key => $value) {
                // 필드명이 _로 시작되는 경우 checkbox
                if($key[0] == '_') {
                    if($value == null) $this->forms[$key] = 0;
                }
            }


            // step5. 데이터 수정
            if($this->forms) {
                //dd($this->forms);
                $this->forms['updated_at'] = date("Y-m-d H:i:s");

                DB::table($this->actions['table'])
                    ->where('id', $this->actions['id'])
                    ->update($this->forms);
            }

            // step6. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookUpdated")) {
                $this->forms = $controller->hookUpdated($this, $this->forms, $this->old);
            }

            // 입력데이터 초기화
            $this->cancel();

            // 팝업창 닫기
            $this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');
        } else {

            $this->popupPermitOpen();
        }
    }




    




}
