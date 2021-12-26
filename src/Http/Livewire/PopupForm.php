<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;

class PopupForm extends Component
{
    use WithFileUploads;
    /**
     * LivePopupForm with AlpineJS
     */
    public $actions;
    public $form=[];

    private $controller;


    public function render()
    {
        return view("jinytable::livewire.popup.form");
    }

    /**
     * 팝업창 관리
     */
    protected $listeners = ['popupFormOpen','popupFormClose','popupFormCreate','edit'];
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

    public function popupFormCreate($value=null)
    {
        return $this->create($value);
    }


    /**
     * 신규 데이터 삽입
     */
    public function create($value=null)
    {
        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookCreated")) {
                //dd($controller);
                $controller->hookCreated($value);
            }
        }

        unset($this->actions['id']);

        // 입력데이터 초기화
        //$this->cancel();

        $this->popupFormOpen();
    }

    public function store()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }



        // 시간정보 생성
        $this->form['created_at'] = date("Y-m-d H:i:s");
        $this->form['updated_at'] = date("Y-m-d H:i:s");

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookStored")) {
                $form = $controller->hookStored($this->form);
            } else {
                $form = $this->form;
            }
        } else {
            $form = $this->form;
        }

        // 데이터 삽입
        if($form) {
            $id = DB::table($this->actions['table'])->insertGetId($form);
        }

        // 입력데이터 초기화
        $this->cancel();

        // 팝업창 닫기
        $this->popupFormClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');

    }


    /**
     * 데이터 수정
     */
    public function edit($id)
    {
        $this->popupFormOpen();

        if($id) {
            $this->actions['id'] = $id;
        }

        if (isset($this->actions['id'])) {
            $row = DB::table($this->actions['table'])->find($this->actions['id']);
            $this->setForm($row);

            //dd($this->form);

            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookEdited")) {
                    $this->form = $controller->hookEdited($this->form);
                }
            }
        }
    }

    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->form[$key] = $value;
        }
    }

    public function update()
    {
        // 수정전, 원본 데이터 읽기
        $origin = DB::table($this->actions['table'])->find($this->actions['id']);

        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookUpdated")) {
                //dd($controller);
                $this->form = $controller->hookUpdated($this->form);
            }
        }



        // 파일 업로드 체크
        $this->fileUpload();
        // uploadfile 필드 조회
        $fields = DB::table('uploadfile')->where('table', $this->actions['table'])->get();
        foreach($fields as $item) {
            $key = $item->field; // 업로드 필드명
            if($origin->$key != $this->form[$key]) {
                ## 이미지를 수정하는 경우, 기존 이미지는 삭제합니다.
                Storage::delete($origin->$key);
            }
        }


        // 데이터 수정
        if($this->form) {
            DB::table($this->actions['table'])
                ->where('id', $this->actions['id'])
                ->update($this->form);
        }

        // 입력데이터 초기화
        $this->cancel();

        // 팝업창 닫기
        $this->popupFormClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }

    public function cancel()
    {
        $this->form = [];
    }


    /**
     * 데이터 삭제
     */

    public $confirm = false;

    public function deleteConfirm()
    {
        $this->confirm = true;
    }

    public function delete()
    {
        $row = DB::table($this->actions['table'])->find($this->actions['id']);

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookDeleted")) {
                $row = $controller->hookDeleted($row);
            }
        }

        // uploadfile 필드 조회
        $fields = DB::table('uploadfile')->where('table', $this->actions['table'])->get();
        foreach($fields as $item) {
            $key = $item->field; // 업로드 필드명
            if (isset($row->$key)) {
                Storage::delete($row->$key);
            }
        }

        //dd($fields);

        // 데이터 삭제
        DB::table($this->actions['table'])
            ->where('id', $this->actions['id'])
            ->delete();

        // 입력데이터 초기화
        $this->cancel();

        // 팝업창 닫기
        $this->popupFormClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }

    /**
     * 파일 업로드
     */
    public function fileUpload()
    {
        // 테이블명과 동일한 폴더에 저장
        $upload_directory = $this->actions['table'];

        // public or private 저장영역 설정
        if(isset($this->actions['visible'])) {
            $visible = $this->actions['visible'];
        } else {
            $visible = "private";
        }

        $appPath = storage_path('app/'.$visible);
        $path = $appPath.DIRECTORY_SEPARATOR.$upload_directory;
        if(!\is_dir($path)) {
            \mkdir($path, 755, true);
        }

        foreach($this->form as $key => $item) {
            if($item instanceof \Livewire\TemporaryUploadedFile) {
                $filename = $item->store($upload_directory, $visible);
                $this->form[$key] = $visible."/".$filename;

                // uploadfile 테이블에 기록
                DB::table('uploadfile')->updateOrInsert([
                    'table' => $this->actions['table'],
                    'field' => $key
                ]);

            }
        }

    }



}
