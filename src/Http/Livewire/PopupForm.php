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

        // 데이터 삽입
        $id = DB::table($this->actions['table'])->insertGetId($this->form);

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
