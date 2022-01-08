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

    public $actions;
    public $table;
    public $forms=[];
    private $controller;
    public $back=true;

    public function render()
    {
        if (isset($this->actions['id'])) {
            // 수정
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

        } else {
            // 생성
            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCreating")) {
                    $controller->hookCreating($this);
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
    }


    public function update()
    {
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
    }


    public function clear()
    {
        $this->forms = [];
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
