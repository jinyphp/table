<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class WireForm extends Component
{
    use WithFileUploads;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Livewire\Upload;

    public $actions;
    public $table;
    public $forms=[];
    private $controller;
    public $back=true;

    public function mount()
    {
        $this->permitCheck();
    }

    public function render()
    {
        if (isset($this->actions['id'])) {
            // 수정
            /*
            $form = DB::table($this->actions['table'])->find($this->actions['id']);
            foreach ($form as $key => $value) {
                $this->forms[$key] = $value;
            }

            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookEdited")) {
                    $this->forms = $controller->hookEdited($this->forms);
                }
            }
            */
            $id = $this->actions['id'];
            $this->edit($id);

        } else {
            // 생성
            /*
            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCreating")) {
                    $controller->hookCreating($this);
                }
            }
            */

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
        //dd($this->forms['sort']);

        if($this->permit['create']) {
            unset($this->actions['id']);

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCreating")) {
                $controller->hookCreating($this, $value);
            }



            //$this->popupFormOpen();

        } else {
            $this->popupPermitOpen();
        }
    }



    public function submit()
    {
        if (isset($this->actions['id'])) {
            $this->update();
        } else {
            $this->store();
        }

        $this->goToIndex();
    }

    public function clear()
    {
        $this->forms = [];
    }

    public function store()
    {
        /*
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
        }

        $this->forms['created_at'] = date("Y-m-d H:i:s");
        $this->forms['updated_at'] = date("Y-m-d H:i:s");

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookStoring")) {
                $form = $controller->hookStoring($this, $this->forms);
            } else {
                $form = $this->forms;
            }
        } else {
            $form = $this->forms;
        }

        $id = DB::table($this->actions['table'])->insertGetId($form);

        $this->forms = [];
        */

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
            $this->cancel();

            // 팝업창 닫기
            //$this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            //$this->emit('refeshTable');

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
            //$this->popupFormOpen();

            if($id) {
                $this->actions['id'] = $id;
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookEditing")) {
                $this->forms = $controller->hookEditing($this, $this->forms);
            }

            if (isset($this->actions['id'])) {
                $row = DB::table($this->actions['table'])->find($this->actions['id']);
                $this->setForm($row);
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
    {   /*
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
        }

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookUpdating")) {
                //dd($controller);
                $this->forms = $controller->hookUpdating($this->forms);
            }
        }

        DB::table($this->actions['table'])
        ->where('id', $this->actions['id'])
        ->update($this->forms);

        $this->forms = [];
        */

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
            $this->cancel();

            // 팝업창 닫기
            //$this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            //$this->emit('refeshTable');
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
        //$this->confirm = true;
        if($this->permit['delete']) {
            $this->confirm = true;
        } else {
            $this->popupPermitOpen();
        }
    }


    public function delete()
    {
        /*
        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookDeleted")) {
                $controller->hookDeleted();
            }
        }

        DB::table($this->actions['table'])->where('id', $this->actions['id'])
            ->delete();
        */

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

            $this->goToIndex();

        } else {
            $this->popupPermitOpen();
        }
    }


    private function goToIndex()
    {
        if ($this->back) {
            return redirect()->route($this->actions['routename'].'.index');
        }
    }


    /** ----- ----- ----- ----- -----
     *  Tab Title Setting popup
     */
    public $popupTabbar = false;
    public $popupTabbarMessage;
    public $popupTabbarConfirm = false;
    public $tabname;
    public $tabid;
    public function popupTabbarClose()
    {
        $this->popupTabbar = false;
        $this->tabid = null;
        $this->tabname = null;
        $this->popupTabbarConfirm = false;
        $this->popupTabbarMessage = null;
    }

    public function popupTabbar($id=null)
    {
        if($id) {
            $this->tabid = $id;
            $tab = DB::table('form_tabs')->where('id',$id)->first();
            $this->tabname = $tab->name;
        }

        $this->popupTabbar = true;
    }

    public function popupTabbarSave()
    {
        if($this->tabid) {
            DB::table('form_tabs')->where('id',$this->tabid)->update(['name'=>$this->tabname]);
        } else {
            $uri = "/".$this->actions['route']['uri'];
            $pos = DB::table('form_tabs')->where('uri',$uri)->max('pos'); //최대값 pos

            DB::table('form_tabs')->insert([
                'uri'=> $uri,
                'name'=> $this->tabname,
                'pos'=> $pos+1
            ]);
        }

        $this->popupTabbarClose();
    }

    public function popupTabbarDelete()
    {
        if($this->tabid) {
            $this->popupTabbarMessage = "정말 삭제하시겠습니까?";
            if($this->popupTabbarConfirm) {
                DB::table('form_tabs')
                ->where('id', $this->tabid)
                ->delete();

                $this->popupTabbarClose();
            } else {
                $this->popupTabbarConfirm = true; //confirm
            }
        } else {
            $this->popupTabbarMessage = "삭제할 텝이 선택되지 않았습니다.";
        }
    }

}
