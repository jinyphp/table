<?php

namespace Jiny\Table\Http\Livewire\Trait;
use Illuminate\Support\Facades\DB;

trait WirePopupDelete
{
    /** ----- ----- ----- ----- -----
     *  데이터 삭제
     *  삭제는 2단계로 동작합니다. 
     *  삭제 버튼을 클릭하면, 실제 동작 버튼이 활성화 됩니다.
     */
    public $popupDelete = false;
    public $confirm = false;
    public $delete_code;
    public $delete_confirm_code;

    public function delete($id=null)
    {
        // 삭제권환 체크
        if($this->permit['delete']) {
            $this->popupDelete = true;

            // 삭제코드
            $this->delete_code = $this->generateRandomString(10);
            $this->delete_confirm_code = null;

        } else {
            $this->popupFormClose();
            $this->popupPermitOpen();
        }
    }

    public function deleteCancel()
    {
        $this->popupDelete = false;
        
        $this->delete_code = null;
        $this->delete_confirm_code = null;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function delete_code_apply()
    {
        $this->delete_confirm_code = $this->delete_code;
    }

    public function delete_code_reload()
    {
        $this->delete_code = $this->generateRandomString(10);
    }

    public function deleteConfirm()
    {
        //dump($this->delete_code);
        //dump($this->delete_confirm_code);

        if($this->delete_confirm_code == $this->delete_code) {
            $this->deleteAction();           

            $this->delete_confirm_code = null;
            $this->delete_code = null;
        } else {
            session()->flash('message', "메뉴코드가 일치하지 않습니다!");
        }
    }

    private function deleteAction()
    {
        $this->popupDelete = false;

        if($this->permit['delete']) {
            $row = DB::table($this->actions['table'])->find($this->actions['id']);

            $form = [];
            foreach($row as $key => $value) {
                $form[$key] = $value;
            }

            // 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookDeleting")) {
                $row = $controller->hookDeleting($this, $form);
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
                $row = $controller->hookDeleted($this, $form);
            }

            // 입력데이터 초기화
            $this->cancel();

            // 팝업창 닫기
            $this->popupFormClose();
            $this->popupDelete = false;

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');

        } else {
            $this->popupFormClose();
            $this->popupPermitOpen();
        }
    }


}