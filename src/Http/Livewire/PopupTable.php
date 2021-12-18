<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PopupTable extends Component
{
    public $actions;
    public $paging = 10;
    public $filter=[];

    public $ids = [];
    public $data=[];

    /**
     * LiveListTable
     */
    public function render()
    {
        $rows = $this->dbFetch();
        return view($this->actions['list'],['rows'=>$rows]);
    }

    private function dbFetch()
    {
        $DB = DB::table($this->actions['table']);
        foreach ($this->filter as $key => $filter) {
            $DB->where($key,'like','%'.$filter.'%');
        }

        $rows = $DB->orderBy('id',"desc")->paginate($this->paging);

        $this->data = [];
        foreach($rows as $i => $item) {
            foreach($item as $k => $v) {
                $this->data[$i][$k] = $v;
            }
        }

        $this->ids = [];
        foreach($this->data as $i => $item) {
            $this->ids[$i] = $item['id'];
        }

        //session()->flash('message',"데이터...");
        return $rows;
    }

    ## 검색
    public function filter_search()
    {
        // 선택항목 초기화
        $this->selectedall = false;
        $this->selected = [];

        //session()->flash('message',"데이터 검색");
    }

    public function filter_reset()
    {
        $this->filter = [];
    }

    protected $listeners = ['refeshTable'];
    public function refeshTable()
    {
    }

    /**
     * 체크박스
     */
    public $selectedall = false;
    public $selected = [];

    // Livewire Hook
    public function updatedSelectedall($value)
    {
        if($value) {
            $this->selected = [];
            foreach($this->ids as $i => $v) {
                $this->selected[$i] = strval($v);
            }
        } else {
            $this->selected = [];
        }
    }

    // Livewire Hook
    public function updatedSelected($value)
    {
        if(count($this->selected) == count($this->ids)) {
            $this->selectedall = true;
        } else {
            $this->selectedall = false;
        }
    }

    /**
     * 선택삭제 팝업창
     */
    public $popupDelete = false;
    public function popupDeleteOpen()
    {
        $this->popupDelete = true;
    }

    public function popupDeleteClose()
    {
        $this->popupDelete = false;
    }

    public function checkeDelete()
    {
        DB::table($this->actions['table'])->whereIn('id', $this->selected)->delete();
        $this->popupDeleteClose();
    }

}
