<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;

class PopupForm extends Component
{
    use WithFileUploads;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;

    /**
     * LivePopupForm with AlpineJS
     */
    public $actions;
    public $form=[];
    public $mode;
    private $controller;


    public function mount()
    {
        $this->permitCheck();
    }

    public function render()
    {
        return view("jinytable::livewire.popup.form");
    }

    /** ----- ----- ----- ----- -----
     *  팝업창 관리
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


    /** ----- ----- ----- ----- -----
     *  신규 데이터 삽입
     */

    public function create($value=null)
    {
        if($this->permit['create']) {
            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCreating")) {
                $controller->hookCreating($this, $value);
            }

            unset($this->actions['id']);
            $this->popupFormOpen();
        } else {
            $this->popupPermitOpen();
        }
    }

    public function store()
    {
        if($this->permit['create']) {

            //유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->form, $this->actions['validate'])->validate();
            }

            // 시간정보 생성
            $this->form['created_at'] = date("Y-m-d H:i:s");
            $this->form['updated_at'] = date("Y-m-d H:i:s");

            // step4. 파일 업로드 체크
            //
            $this->fileUpload();

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookStoring")) {
                $form = $controller->hookStoring($this->form);
            } else {
                $form = $this->form;
            }


            // 데이터 삽입
            if($form) {
                $id = DB::table($this->actions['table'])->insertGetId($form);

                // 컨트롤러 메서드 호출
                if ($controller = $this->isHook("hookStored")) {
                    $controller->hookStored($this->form, $id);
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

    // 취소 및 form 입력항목 초기화
    public function cancel()
    {
        $this->form = [];
    }


    /** ----- ----- ----- ----- -----
     *  데이터 수정
     */

    public function edit($id)
    {
        if($this->permit['update']) {
            $this->popupFormOpen();

            if($id) {
                $this->actions['id'] = $id;
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEditing")) {
                $this->form = $controller->hookEditing($this->form);
            }

            if (isset($this->actions['id'])) {
                $row = DB::table($this->actions['table'])->find($this->actions['id']);
                $this->setForm($row);
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEdited")) {
                $this->form = $controller->hookEdited($this->form);
            }
        } else {

            $this->popupPermitOpen();
        }
    }

    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->form[$key] = $value;
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
                $validator = Validator::make($this->form, $this->actions['validate'])->validate();
            }

            // step3. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookUpdating")) {
                $this->form = $controller->hookUpdating($this->form);
            }


            // step4. 파일 업로드 체크
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


            // step5. 데이터 수정
            if($this->form) {
                DB::table($this->actions['table'])
                    ->where('id', $this->actions['id'])
                    ->update($this->form);
            }

            // step6. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookUpdated")) {
                $this->form = $controller->hookUpdated($this->form);
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
            $this->popupFormClose();
            $this->popupPermitOpen();
        }
    }

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
            $this->cancel();

            // 팝업창 닫기
            $this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');

        } else {
            $this->popupFormClose();
            $this->popupPermitOpen();
        }
    }


    /** ----- ----- ----- ----- -----
     *  파일 업로드
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
