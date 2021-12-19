<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class WireForm extends Component
{
    public $actions;

    public $table;
    public $back=true;
    public $form=[];



    private $controller;
    public function mount()
    {
        // wire conntect
        if(isset($this->actions['controller'])) {
            $this->controller = $this->actions['controller']::getInstance($this);
        }
    }

    public function render()
    {
        if (isset($this->actions['id'])) {
            $form = DB::table($this->actions['table'])->find($this->actions['id']);
            foreach ($form as $key => $value) {
                $this->form[$key] = $value;
            }
        }

        return view("jinytable::livewire.form");
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


    public function store()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        $this->form['created_at'] = date("Y-m-d H:i:s");
        $this->form['updated_at'] = date("Y-m-d H:i:s");

        $id = DB::table($this->actions['table'])->insertGetId($this->form);

        $this->form = [];
    }

    public function update()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        DB::table($this->actions['table'])
        ->where('id', $this->actions['id'])
        ->update($this->form);

        $this->form = [];
    }

    public function clear()
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
        DB::table($this->actions['table'])->where('id', $this->actions['id'])
            ->delete();

        $this->goToIndex();
    }

    private function goToIndex()
    {
        if ($this->back) {
            return redirect()->route($this->actions['routename'].'.index');
        }
    }

}
