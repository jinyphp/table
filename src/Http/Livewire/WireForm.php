<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class WireForm extends Component
{
    public $actions;

    public $table;
    public $back=true;
    public $form=[];



    public function mount()
    {

    }

    public function render()
    {

        if (isset($this->actions['id'])) {
            $form = DB::table($this->actions['table'])->find($this->actions['id']);
            foreach ($form as $key => $value) {
                $this->form[$key] = $value;
            }
        }
        return view($this->actions['form']);
    }


    public function submit()
    {

        if (isset($this->actions['id'])) {
            $this->updateSubmit();
        } else {
            $this->createSubmit();
        }


        if ($this->back) {
            return redirect()->route($this->actions['routename'].'.index');
        }

    }


    public function createSubmit()
    {
        $this->form['created_at'] = date("Y-m-d H:i:s");
        $this->form['updated_at'] = date("Y-m-d H:i:s");
        $id = DB::table($this->actions['table'])->insertGetId($this->form);
        $this->form = [];
    }

    public function updateSubmit()
    {
        DB::table($this->actions['table'])
        ->where('id', $this->actions['id'])
        ->update($this->form);

        $this->form = [];

    }

    public function clear()
    {
        $this->form = [];
    }

    public function delete()
    {
        DB::table($this->actions['table'])->where('id', $this->actions['id'])
            ->delete();

        if ($this->back) {
            return redirect()->route($this->actions['routename'].'.index')->with('message',"자료가 삭제되었습니다.");
        }
    }

}
