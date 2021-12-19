<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class PopupForm extends Component
{
    /**
     * LivePopupForm with AlpineJS
     */
    public $actions;
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
        return view("jinytable::livewire.popup.form");
    }

    /**
     * 팝업창 관리
     */
    protected $listeners = ['popupFormOpen','popupFormClose','popupFormCreate','edit'];
    public $popupForm = false;
    public function popupFormOpen()
    {
        $this->popupForm = true;
        $this->confirm = false;
    }

    public function popupFormClose()
    {
        $this->popupForm = false;
    }

    public function popupFormCreate()
    {
        return $this->create();
    }


    /**
     * 신규 데이터 삽입
     */
    public function create()
    {
        // 컨트롤러 메서드 호출
        if(method_exists($this->actions['controller'], "hookCreated")) {
            $this->controller->hookCreated();
        }

        unset($this->actions['id']);

        // 입력데이터 초기화
        $this->cancel();

        $this->popupFormOpen();
    }

    public function store()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        // 시간정보 생성
        $this->form['created_at'] = date("Y-m-d H:i:s");
        $this->form['updated_at'] = date("Y-m-d H:i:s");

        // 컨트롤러 메서드 호출
        if(method_exists($this->actions['controller'], "hookStored")) {
            $form = $this->controller->hookStored($this->form);
        } else {
            $form = $this->form;
        }

        // 데이터 삽입
        $id = DB::table($this->actions['table'])->insertGetId($form);

        // 입력데이터 초기화
        $this->cancel();

        // 팝업창 닫기
        $this->popupFormClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');

    }


    /**
     * 데이터 수정
     */
    public function edit($id)
    {
        $this->popupFormOpen();
        $this->actions['id'] = $id;

        if (isset($this->actions['id'])) {
            $row = DB::table($this->actions['table'])->find($this->actions['id']);
            $this->setForm($row);

            // 컨트롤러 메서드 호출
            if(method_exists($this->actions['controller'], "hookEdited")) {
                $this->form = $this->controller->hookEdited($this->form);
            }
        }
    }

    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->form[$key] = $value;
        }
    }

    public function update()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        // 컨트롤러 메서드 호출
        if(method_exists($this->actions['controller'], "hookUpdated")) {
            $this->form = $this->controller->hookUpdated($this->form);
        }

        // 데이터 수정
        DB::table($this->actions['table'])
        ->where('id', $this->actions['id'])
        ->update($this->form);

        // 입력데이터 초기화
        $this->cancel();

        // 팝업창 닫기
        $this->popupFormClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }

    public function cancel()
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
        if(method_exists($this->actions['controller'], "hookDeleted")) {
            $this->controller->hookDeleted();
        }

        // 데이터 삭제
        DB::table($this->actions['table'])
            ->where('id', $this->actions['id'])
            ->delete();

        // 입력데이터 초기화
        $this->cancel();

        // 팝업창 닫기
        $this->popupFormClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }



}
