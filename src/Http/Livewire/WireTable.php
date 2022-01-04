<?php
/**
 *
 */
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class WireTable extends Component
{
    use WithPagination;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;

    public $actions;


    public $paging = 10;
    public function mount()
    {
        $this->permitCheck();

        // 페이징 초기화
        if (isset($this->actions['paging'])) {
            $this->paging = $this->actions['paging'];
        }
    }


    /** ----- ----- ----- ----- -----
     *  Table
     */
    public function render()
    {
        if(isset($this->actions['table']) && $this->actions['table']) {
            $this->setTable($this->actions['table']);
        } else {
            // 테이블명이 없는 경우
            return view("jinytable::error.tablename_none");
        }


        // 컨트롤러 메서드 호출
        if ($controller = $this->isHook("HookIndexing")) {
            $result = $controller->HookIndexing($this);
            if($result) {
                // 반환값이 있는 경우, 출력하고 이후동작을 중단함.
                return $result;
            }
        }

        if(isset($this->actions['table']) && $this->actions['table']) {

            $rows = $this->dataFetch($this->actions);
            if($rows) {
                // 컨트롤러 메서드 호출
                if ($controller = $this->isHook("HookIndexed")) {
                    $rows = $controller->HookIndexed($this, $rows);

                    if(is_null($rows)) {
                        return view("jinytable::error.message",[
                            'message'=>"HookIndexed() 호출 반환값이 없습니다."
                        ]);
                    }
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

            // 테이블 출력형식 레이아아웃
            if(isset($this->actions['view_main_layout']) && $this->actions['view_main_layout']) {
                $view_layout = $this->actions['view_main_layout'];
            } else {
                $view_layout = "jinytable::livewire.table";
            }

            return view($view_layout,[
                'rows'=>$rows,
                'popupEdit'=>$funcEditPopup,
                'editLink'=>$funcEditLink
            ]);

        }
    }

    protected $listeners = ['refeshTable'];
    public function refeshTable()
    {
        // 페이지를 재갱신 합니다.
    }

    /** ----- ----- ----- ----- -----
     *  Read Data
     */
    public $dataType = "table";
    protected $dbSelect;
    public function setTable($table)
    {
        // 외부 별도 클래스로 처리
        $this->dbSelect = new \Jiny\Table\Database\Select($table);
        return $this;
    }
    public function DB()
    {
        return $this->dbSelect->DB;
    }
    protected function dataFetch($actions)
    {
        $DB = $this->DB();
        if($DB) {
            //  2.제한조건 적용
            if(isset($this->actions['where']) && is_array($this->actions['where'])) {
                $this->dbSelect->wheres($this->actions['where']);
            }

            //  3.사용자필터 조건적용
            /*
            foreach ($this->filter as $key => $filter) {
                $DB->where($key,'like','%'.$filter.'%');
            }
            */
            $this->dbSelect->filters($this->filter);


            //  4.Sort
            /*
            if (empty($this->sort)) {
                $DB->orderBy('id',"desc");
            } else {
                foreach($this->sort as $key => $value) {
                    $DB->orderBy($key, $value);
                }
            }
            */
            $this->dbSelect->sort($this->sort);

            //  5.최종 데이터 읽기
            //  페이징이 없는 경우, 전체 읽기
            if(isset($this->paging) && is_numeric($this->paging) ) {
                $rows = $this->dbSelect->paginate($this->paging);
            } else {
                $rows = $this->dbSelect->get();
            }

            //  6.
            $this->setData($rows);
            $this->setIds();

            // session()->flash('message',"데이터...");
            return $rows;
        }

        return [];
    }

    public $data=[];
    protected function setData($rows)
    {
        $this->data = [];
        foreach($rows as $i => $item) {
            foreach($item as $k => $v) {
                $this->data[$i][$k] = $v;
            }
        }

        return $this;
    }

    public $ids = [];
    protected function setIds()
    {
        $this->ids = [];
        foreach($this->data as $i => $item) {
            $this->ids[$i] = $item['id'];
        }
    }

    # 컬럼 필드 정렬 적용
    public $sort=[];
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


    # 검색
    public $filter=[];
    public function filter_search()
    {
        // 선택항목 초기화
        $this->selectedall = false;
        $this->selected = [];

        // session()->flash('message',"데이터 검색");
    }

    public function filter_reset()
    {
        $this->filter = [];
        $this->sortClear();
    }


    /** ----- ----- ----- ----- -----
     *  checkBox Selecting
     */

    public $selectedall = false;
    public $selected = [];

    # Livewire Hook
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

    # Livewire Hook
    public function updatedSelected($value)
    {
        if(count($this->selected) == count($this->ids)) {
            $this->selectedall = true;
        } else {
            $this->selectedall = false;
        }
    }

    # Livewire Hook
    public function updatedPaging($value)
    {
        // 페이지목록 수 변경시,
        // 기존에 선택된 체크박스는 초기화 함.
        $this->selectedall = false;
        $this->selected = [];
    }


    /** ----- ----- ----- ----- -----
     *  delete
     */

    # 선택삭제 팝업창
    public $popupDelete = false;

    public function popupDeleteOpen()
    {
        if($this->permit['delete']) {
            $this->popupDelete = true;
        } else {
            $this->popupPermitOpen();
        }
    }

    public function popupDeleteClose()
    {
        // 삭제 확인창을 닫기
        $this->popupDelete = false;
    }

    public function confirmDelete()
    {
        $this->checkeDelete();
    }

    public function checkeDelete()
    {
        if($this->permit['delete']) {

            // 1.컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCheckDeleting")) {
                    $controller->hookCheckDeleting($this->selected);
                }
            }

            // 2.uploadfile 필드 조회
            $fields = DB::table('uploadfile')->where('table', $this->actions['table'])->get();
            $rows = DB::table($this->actions['table'])->whereIn('id', $this->selected)->get();
            foreach ($rows as $row) {
                foreach($fields as $item) {
                    $key = $item->field; // 업로드 필드명
                    if (isset($row->$key)) {
                        Storage::delete($row->$key);
                    }
                }
            }

            // 3.복수의 ids를 삭제합니다.
            if($this->dataType == "table") {
                DB::table($this->actions['table'])->whereIn('id', $this->selected)->delete();
            } else if($this->dataType == "uri") {

            } else if($this->dataType == "file") {

            }


            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookCheckDeleted")) {
                    $controller->hookCheckDeleted($this->selected);
                }
            }

            // 4.페이지목록 수 변경시,
            // 기존에 선택된 체크박스는 초기화 함.
            $this->selectedall = false;
            $this->selected = [];

            $this->popupDeleteClose();

        } else {
            $this->popupPermitOpen();
        }
    }

}
