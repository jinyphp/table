<?php
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class TreeDeleteCreate extends Component
{







    /**
     * 댓글 삭제
     */
    public function delete($id)
    {

    }




    /**
     * 댓글 수정
     */
    public $editmode=null;
    public function edit($id)
    {
        $node = $this->findNode($this->rows, $id);
        if($node['user_id'] == Auth::user()->id) {
            $this->forms['content'] = $node['content'];

            $this->editmode = "edit";
            $this->reply_id = $id;
        }
    }

    public function update()
    {
        DB::table($this->tablename)
            ->where('id',$this->reply_id)
            ->update($this->forms);

        $this->forms = [];
        $this->editmode = null;
        $this->reply_id = null;
    }








}
