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

use \Jiny\Table\Http\Livewire\TableCheckDelete;
class TableDeleteCreate extends TableCheckDelete
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
                    ."::table.table_filter_create.layout";
            }
        }

        parent::mount();
    }

    public function render()
    {
        //dd("aaa");
        return parent::render();
    }


    /**
     * 라이브와이어 이벤트
     */
    public function create()
    {
        $this->dispatch('createPopupForm');
    }

    public function edit($id)
    {
        $this->dispatch('editPopupForm', $id);
    }
}
