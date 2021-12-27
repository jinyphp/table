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


    public function render()
    {
        if (isset($this->actions['id'])) {
            // 수정
            $form = DB::table($this->actions['table'])->find($this->actions['id']);
            foreach ($form as $key => $value) {
                $this->form[$key] = $value;
            }

            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookEdited")) {
                    $this->form = $controller->hookEdited($this->form);
                }
            }

        } else {
            // 생성
            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCreating")) {
                    $controller->hookCreating();
                }
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

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookStoring")) {
                $form = $controller->hookStoring($this->form);
            } else {
                $form = $this->form;
            }
        } else {
            $form = $this->form;
        }

        $id = DB::table($this->actions['table'])->insertGetId($form);

        $this->form = [];
    }


    public function update()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookUpdating")) {
                //dd($controller);
                $this->form = $controller->hookUpdating($this->form);
            }
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
        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookDeleted")) {
                $controller->hookDeleted();
            }
        }

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
