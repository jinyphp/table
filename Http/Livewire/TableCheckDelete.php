<?php
namespace Jiny\Table\Http\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

use Livewire\WithFileUploads;
use Livewire\Attributes\On;

use \Jiny\Table\Http\Livewire\TableFilter;
class TableCheckDelete extends TableFilter
{
    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Hook;
    use \Jiny\WireTable\Http\Trait\Permit;
    use \Jiny\WireTable\Http\Trait\UploadSlot;

    public function mount()
    {

        // 뷰 테이블 레이아웃 지정
        if(!$this->viewFile) {
            if(isset($this->actions['view']['table'])) {
                $this->viewFile = $this->actions['view']['table'];
            } else {
                $this->viewFile = "jiny-table"
                    ."::table.table_delete.layout";
            }
        }

        parent::mount();
    }


    public function render()
    {
        //dd("bbb");
        return parent::render();
    }



    public $popupWindowWidth = "4xl";
    /** ----- ----- ----- ----- -----
     *  delete
     */

    # 선택삭제 팝업창
    public $checkDelete = false;
    public $checkDeleteConfirm = false;

    protected $listeners = ['refeshTable'];
    #[On('refeshTable')]
    public function refeshTable()
    {
        // 페이지를 재갱신 합니다.
    }

    /** ----- ----- ----- ----- -----
     *  checkBox Selecting
     */
    public $selectedall = false;
    public $selected = [];
    public $selected_count = 0;


    // model.live로 selectedall 클릭시 호출됩니다.
    public function updatedSelectedall($value)
    {
        if($value) {
            $this->selected = []; // 초기화

            // 전체 선택 체크, id값 지정
            foreach($this->ids as $i => $v) {
                $this->selected[$i] = strval($v);
            }

        } else {
            // 모든 선택 해제
            $this->selected = [];
        }

        // 선택된 true 갯수 확인
        $this->selected_count = count($this->selected);
        if($this->selected_count == 0) {
            $this->popupCheckDeleteClose();
        }
    }

    # Livewire Hook
    public function updatedSelected($value)
    {
        if(count($this->selected) == count($this->ids)) {
            $this->selectedall = true;
        } else {
            $this->selectedall = false;
        }

        // 선택된 true 갯수 확인
        $this->selected_count = count($this->selected);
        if($this->selected_count == 0) {
            $this->popupCheckDeleteClose();
        }
    }


    # Livewire Hook
    public function updatedPaging($value)
    {
        // 페이지목록 수 변경시,
        // 기존에 선택된 체크박스는 초기화 함.
        $this->selectedall = false;
        $this->selected = [];
    }



    /**
     * 삭제 확인창
     */
    public function popupCheckDelete()
    {
        if($this->permit['delete']) {
            $this->checkDelete = true;
        } else {
            $this->popupPermitOpen();
        }
    }

    public function popupCheckDeleteClose()
    {
        // 삭제 확인창을 닫기
        $this->checkDelete = false;
        $this->checkDeleteConfirm = false;
    }

    public function checkeDeleteConfirm()
    {
        $this->checkDeleteConfirm = true;
    }

    public function checkeDeleteRun()
    {
        if($this->permit['delete']) {

            // 1.컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCheckDeleting")) {
                if(method_exists($controller, "hookCheckDeleting")) {
                    $controller->hookCheckDeleting($this, $this->selected);
                }
            }

            // 3.복수의 ids를 삭제합니다.
            DB::table($this->tablename)
                ->whereIn('id', $this->selected)->delete();

            // 4. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCheckDeleted")) {
                if(method_exists($controller, "hookCheckDeleted")) {
                    $controller->hookCheckDeleted($this, $this->selected);
                }
            }

            // 5. 기존에 선택된 체크박스는 초기화 함.
            $this->selectedall = false;
            $this->selected = [];
            $this->selected_count = 0;

            // Livewire Table을 갱신을 호출합니다.
            $this->dispatch('refeshTable');

            $this->popupCheckDeleteClose();

        } else {
            $this->popupPermitOpen();
        }
    }


}
