<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class WireTable extends Component
{
    use WithPagination;

    public $actions;
    public $paging = 10;

    public $filter=[];
    public $ids = [];
    public $data=[];
    public $sort=[];

    public function mount()
    {
        // 페이징 초기화
        if (isset($this->actions['paging'])) {
            $this->paging = $this->actions['paging'];
        }
    }

    /**
     * LiveListTable
     */
    public function render()
    {
        $rows = $this->dbFetch($this->actions);

        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "HookIndexed")) {
                $controller->hookIndexed($rows);
            }
        }

        // 내부함수 생성
        // 팝업창 폼을 활성화 합니다.
        $funcEditPopup = function ($item, $title)
        {
            $link = xLink($title)->setHref("javascript: void(0);");
            $link->setAttribute("wire:click", "$"."emit('edit','".$item->id."')");

            if (isset($item->enable)) {
                if($item->enable) {
                    return $link;
                } else {
                    return xSpan($link)->style("text-decoration:line-through;");
                }
            }

            return $link;
        };

        // 내부함수 생성
        // form 페이지로 url을 이동합니다.
        $rules = $this->actions;
        $funcEditLink = function ($item, $title) use ($rules)
        {
            $link = xLink($title)->setHref(route($rules['routename'].".edit", $item->id));
            if($item->enable) {
                return $link;
            } else {
                return xSpan($link)->style("text-decoration:line-through;");
            }
            return $link;
        };


        return view("jinytable::livewire.table",[
            'rows'=>$rows,
            'popupEdit'=>$funcEditPopup,
            'editLink'=>$funcEditLink
        ]);
    }

    private function dbFetch($actions)
    {
        // 테이블 설정
        $DB = DB::table($this->actions['table']);

        // 제한조건 적용
        if(isset($this->actions['where']) && is_array($this->actions['where'])) {
            foreach ($this->actions['where'] as $key => $where) {
                $DB->where($key,$where);
            }
        }

        // 사용자필터 조건적용
        foreach ($this->filter as $key => $filter) {
            $DB->where($key,'like','%'.$filter.'%');
        }

        // Sort
        if (empty($this->sort)) {
            $DB->orderBy('id',"desc");
        } else {
            foreach($this->sort as $key => $value) {
                $DB->orderBy($key, $value);
            }
        }

        // 최종 데이터 읽기
        // 페이징이 없는 경우, 전체 읽기
        if(isset($this->paging) && is_numeric($this->paging) ) {
            $rows = $DB->paginate($this->paging);
        } else {
            $rows = $DB->get();
        }

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

    ## 정렬
    public function orderBy($key)
    {
        if (isset($this->sort[$key])) {
            // 토글
            if($this->sort[$key] == "desc") {
                $this->sort[$key] = "asc";
            } else {
                $this->sort[$key] = "desc";
            }
        } else {
            // 설정
            $this->sort[$key] = "desc";
        }
    }

    public function getOrderBy($key)
    {
        if (isset($this->sort[$key])) {
            return $this->sort[$key];
        }
    }

    private function sortClear()
    {
        $this->sort = [];
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
        $this->sortClear();
    }

    protected $listeners = ['refeshTable'];
    public function refeshTable()
    {
        ## 페이지를 재갱신 합니다.
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

    // Livewire Hook
    public function updatedPaging($value)
    {
        ## 페이지목록 수 변경시,
        ## 기존에 선택된 체크박스는 초기화 함.
        $this->selectedall = false;
        $this->selected = [];
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
        // 삭제 확인창을 닫기
        $this->popupDelete = false;
    }

    public function checkeDelete()
    {
        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookCheckDelete")) {
                $controller->hookCheckDelete($this->selected);
            }
        }

        // 복수의 ids를 삭제합니다.
        DB::table($this->actions['table'])->whereIn('id', $this->selected)->delete();

        ## 페이지목록 수 변경시,
        ## 기존에 선택된 체크박스는 초기화 함.
        $this->selectedall = false;
        $this->selected = [];

        $this->popupDeleteClose();
    }

}
