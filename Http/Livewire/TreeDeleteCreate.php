<?php
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class TreeDeleteCreate extends Component
{
    use WithFileUploads;
    public $actions = [];

    public $post_id;
    public $rows = [];

    public $last_id;

    public $forms=[];
    public $post_comment = false;

    public $code;
    public $viewFile;

    public $tablename;
    public $editmode;
    public $reply_id;

    //public $popupForm = false;

    public function mount()
    {
        $this->reply_id = 0;
        $this->tablename = $this->actions['table']['name'];

        // if(!$this->viewFile) {
        //     $this->viewFile = 'jinyerp-hr::admin.organization.table';
        // }

        // 뷰 테이블 레이아웃 지정
        if(!$this->viewFile) {
            if(isset($this->actions['view']['table'])) {
                $this->viewFile = $this->actions['view']['table'];
            } else {
                $this->viewFile = "jiny-table"
                    ."::tree.table";
            }
        }

    }

    public function render()
    {
        $rows = DB::table($this->tablename)
                ->orderBy('level',"desc")
                ->get();

        $this->rows = [];
        foreach($rows as $item) {
            $id = $item->id;
            // 객체를 배열로 변환
            $this->rows[$id] = get_object_vars($item);
        }

        $this->tree();

        return view($this->viewFile);
    }


    private function tree()
    {
        foreach($this->rows as &$item) {
            $id = $item['id'];
            if($item['ref']) {
                $ref = $item['ref'];
                if(!isset($this->rows[$ref]['items'])) {
                    $this->rows[$ref]['items'] = [];
                }
                $this->rows[$ref]['items'] []= $item;

                unset($this->rows[$id]);
            }
        }
    }

    public function create()
    {
        //$this->popupForm = true;
        $this->dispatch('createPopupForm');
    }

    public function edit($id)
    {
        $this->dispatch('editPopupForm', $id);
    }

    // 하위 계층
    public function reply($id, $level)
    {
        $this->dispatch('replyPopupForm', $id, $level);
    }

    #[On('refeshTable')]
    public function reflash()
    {
        //dd('reflash');
    }


}
