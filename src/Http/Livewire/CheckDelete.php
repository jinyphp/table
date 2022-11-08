<?php
namespace Jiny\Table\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait CheckDelete
{
    /** ----- ----- ----- ----- -----
     *  checkBox Selecting
     */

    public $selectedall = false;
    public $selected = [];

    public $delete_code;
    public $delete_confirm_code;

    # Livewire Hook
    public function updatedSelectedall($value)
    {
        if($value) {
            $this->selected = [];
            foreach($this->ids as $i => $v) {
                $this->selected[$i] = strval($v);
            }
        } else {
            $this->selected = [];
        }

        //$this->emit('deleteCheck', $this->selected);
    }

    # Livewire Hook
    public function updatedSelected($value)
    {
        if(count($this->selected) == count($this->ids)) {
            $this->selectedall = true;
        } else {
            $this->selectedall = false;
        }

        //$this->emit('deleteCheck', $this->selected);
    }

    # Livewire Hook
    public function updatedPaging($value)
    {
        // 페이지목록 수 변경시,
        // 기존에 선택된 체크박스는 초기화 함.
        $this->selectedall = false;
        $this->selected = [];
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


    /** ----- ----- ----- ----- -----
     *  delete
     */

    # 선택삭제 팝업창
    public $popupDelete = false;

    public function popupDeleteOpen()
    {
        // 삭제권환
        if($this->permit['delete']) {
            $this->popupDelete = true;

            // 삭제코드
            $this->delete_code = $this->generateRandomString(10);
            $this->delete_confirm_code = null;

        } else {
            $this->popupPermitOpen();
        }
    }

    public function popupDeleteClose()
    {
        // 삭제 확인창을 닫기
        $this->popupDelete = false;

        $this->delete_code = null;
        $this->delete_confirm_code = null;
    }

    public function deleteCancel()
    {
        $this->popupDelete = false;
        
        $this->delete_code = null;
        $this->delete_confirm_code = null;
    }

    // alias
    public function deleteConfirm()
    {
        $this->confirmDelete();
    }

    public function confirmDelete()
    {
        if($this->delete_confirm_code == $this->delete_code) {
            $this->checkeDelete();         

            $this->delete_confirm_code = null;
            $this->delete_code = null;
        } else {
            session()->flash('message', "메뉴코드가 일치하지 않습니다!");
        }        
    }

    public function checkeDelete()
    {
        if($this->permit['delete']) {

            // 1.컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCheckDeleting")) {
                    $controller->hookCheckDeleting($this->selected);
                }
            }

            // 2.uploadfile 필드 조회
            $fields = DB::table('uploadfile')->where('table', $this->actions['table'])->get();
            $rows = DB::table($this->actions['table'])->whereIn('id', $this->selected)->get();
            foreach ($rows as $row) {
                foreach($fields as $item) {
                    $key = $item->field; // 업로드 필드명
                    if (isset($row->$key)) {
                        Storage::delete($row->$key);
                    }
                }
            }

            // 3.복수의 ids를 삭제합니다.
            if($this->dataType == "table") {
                DB::table($this->actions['table'])->whereIn('id', $this->selected)->delete();
            } else if($this->dataType == "uri") {

            } else if($this->dataType == "file") {

            }


            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCheckDeleted")) {
                    $controller->hookCheckDeleted($this->selected);
                }
            }

            // 4.페이지목록 수 변경시,
            // 기존에 선택된 체크박스는 초기화 함.
            $this->selectedall = false;
            $this->selected = [];

            $this->popupDeleteClose();

        } else {
            $this->popupPermitOpen();
        }
    }

    
}
