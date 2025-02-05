<?php
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Contracts\Container\Container;

/**
 * 필터 조건을 포함한 테이블 출력
 */
class TableFilter extends Component
{
    use WithPagination;
    use \Jiny\WireTable\Http\Trait\Permit;
    use \Jiny\WireTable\Http\Trait\Hook;

    public $actions;

    // 테이블
    public $tablename;
    public $paging = 5;
    public $admin_prefix;
    public $message;

    // 추출된 데이터 목록 (array)
    public $data=[];
    public $table_columns=[];

    // 화면
    public $viewFile, $viewTable, $viewList, $viewFilter;

    // 테이블에서 팝업 처리
    public $popupForm = false;
    public $forms = [];

    public function mount()
    {
        // 테이블명 지정
        if(!$this->tablename) {
            if(isset($this->actions['table']['name'])) {
                $this->tablename = $this->actions['table']['name'];
            }
        }


        // admin 접속경로 prefix
        if(function_exists('admin_prefix')) {
            $this->admin_prefix = admin_prefix();
        } else {
            $this->admin_prefix = "admin";
        }

        $this->permitCheck();

        // 페이징 초기화
        if (isset($this->actions['paging'])) {
            $this->paging = $this->actions['paging'];
        }

        // 목록 뷰 파일 지정
        if(!$this->viewList) {
            if(isset($this->actions['view']['list'])) {
                $this->viewList = $this->actions['view']['list'];
            }
        }

        if(!$this->viewFilter) {
            if(isset($this->actions['view']['filter'])) {
                $this->viewFilter = $this->actions['view']['filter'];
            }
        }
    }


    /**
     * 목록 출력
     */
    public function render()
    {
        //dd("ccc");

        // 1. 데이터 테이블 체크
        if(!$this->tablename) {
            // 테이블명이 없는 경우
return <<<BLADE
    <div class="alert alert-danger">
        테이블명이 지정되어 있지 않습니다.
    </div>
BLADE;
        }


        // 2. 후킹_before :: 컨트롤러 메서드 호출
        // DB 데이터를 조회하는 방법들을 변경하려고 할때 유용합니다.
        if ($controller = $this->isHook("HookIndexing")) {
            $result = $this->controller->hookIndexing($this);
            if($result) {
                // 반환값이 있는 경우, 출력하고 이후동작을 중단함.
return <<<BLADE
<div class="alert alert-danger">
    <div>stop: hookIndexing:</div>
    <span>{{$result}}</span>
</div>
BLADE;
            }
        }


        // 3. DB에서 데이터를 읽어 옵니다.
        $this->setTable($this->tablename);
        $rows = $this->dataFetch($this->actions);
        //dd($rows);
        $totalPages = $rows->lastPage();
        $currentPage = $rows->currentPage();


        // 4. 후킹_after :: 읽어온 데이터를 별도로
        // 추가 조작이 필요한 경우 동작 합니다. (단, 데이터 읽기가 성공한 경우)
        if($rows) {
            if ($controller = $this->isHook("HookIndexed")) {
                $rows = $this->controller->hookIndexed($this, $rows);
                if(is_array($rows) || is_object($rows)) {
                    // 반환되는 Hook 값은, 배열 또는 객체값 이어야 합니다.
                    // 만일 오류를 발생하고자 한다면, 다른 문자열 값을 출력합니다.
                } else {
                    $message = "HookIndexed() 호출 반환값이 없습니다.";
return <<<BLADE
<div class="alert alert-danger">
    <div>stop: hookIndexing:</div>
    <span>{{$message}}</span>
</div>
BLADE;
                }
            }
        }

        $this->toData($rows); // rows를 data 배열에 복사해 둡니다.


        // 6. 출력 레이아아웃
        // if(!$this->viewFile) {
        //     $this->viewFile = "jiny-table"."::table.table_filter.layout";
        // }
        // 뷰 테이블 레이아웃 지정
        if(!$this->viewFile) {
            if(isset($this->actions['view']['table'])) {
                $this->viewFile = $this->actions['view']['table'];
            } else {
                $this->viewFile = "jiny-table"
                    ."::table.table_filter.layout";
            }
        }


        $view_layout = $this->getViewMainLayout();
        return view($this->viewFile,[
            'rows'=>$rows,
            'totalPages'=>$totalPages,
            'currentPage'=>$currentPage
        ]);

    }

    private function toData($rows)
    {
        $rows = $rows->keyBy('id');
        $this->data = get_object_vars($rows);
        return $this;
    }

    public function getRow($id=null)
    {
        if($id) {
            return $this->data[$id];
        }
        return $this->data;
    }

    // 화면에 출력할 테이블 레이아웃을 지정합니다.
    protected function getViewMainLayout()
    {
        if($this->viewTable) {
            return $this->viewTable;
        }

        // 사용자가 지정한 table 레이아웃이 있는 경우 적용!
        if(isset($this->actions['view']['table'])) {
            if($this->actions['view']['table']) {
                $this->viewTable = $this->actions['view']['table'];
                return $this->viewTable;
            }
        }

        // 기본값
        $this->viewTable = "jiny-site"."::table.basic.layout";
        return $this->viewTable;
    }





    /**
     * 컨트롤러에서 선안한 메소드를 호출
     */
    public function hook($method, ...$args) {
        //dd($method);
        $this->call($method, $args);
    }
    public function call($method, ...$args)
    {
        //dd($method);
        if($controller = $this->isHook($method)) {
            if(method_exists($controller, $method)) {
                return $controller->$method($this, $args[0]);
            }
        }
    }


    public function columnHidden($col_id)
    {
        $row = DB::table('table_columns')->where('id',$col_id)->first();
        if($row->display) {
            DB::table('table_columns')->where('id',$col_id)->update(['display'=>""]);
        } else {
            DB::table('table_columns')->where('id',$col_id)->update(['display'=>"true"]);
        }
    }


    //
    public function request($key=null)
    {
        if($key) {
            if(isset($this->actions['request'][$key])) {
                return $this->actions['request'][$key];
            }

            return null;
        }

        return $this->actions['request'];
    }



    /** ----- ----- ----- ----- -----
     *  Read Data
     */
    public $dataType = "table";
    protected $dbSelect;
    protected $_db;

    // 테이블을 지정합니다.
    public function setTable($table)
    {
        $this->_db = DB::table($table);
        return $this;
    }

    public function database()
    {
        return $this->_db;
    }

    public function db()
    {
        return $this->_db;
    }



    protected function dataFetch($actions)
    {
        $_db = $this->_db;
        if($_db) {

            //dd($actions);

            // DB 검색조건 적용
            if(isset($actions['where'])) {
                if(is_array($actions['where'])) {
                    foreach($actions['where'] as $key => $value) {
                        if(!is_numeric($key) && is_array($value)) {
                            $this->_db->whereIn($key, $value);
                        }
                        else if(!is_numeric($key)) {
                            $this->_db->where($key, $value);
                        }
                    }
                }
            }


            if(isset($actions['table']['where'])) {
                if(is_array($actions['table']['where'])) {

                    foreach($actions['table']['where'] as $key => $value) {
                        if(!is_numeric($key) && is_array($value)) {
                            $this->_db->whereIn($key, $value);
                        }
                        else if(!is_numeric($key)) {
                            $this->_db->where($key, $value);
                        }
                    }
                }
            }
            //dd($actions);

            // Form에서 사용자 필터 조건을 적용한 경우
            // where 조건 추가
            foreach ($this->filter as $key => $filter) {
                $_db->where($key,'like','%'.$filter.'%');
            }

            // 쿼리스트링으로 filter를 지정한 경우
            if(isset($this->actions['filter'])) {
                foreach($this->actions['filter'] as $key => $value) {
                    if(isset($value[0]) && $value[0] == '>') {
                        if(isset($value[1]) && $value[1] == '=') {
                            $_db->where($key,'>=',substr($value,2));
                        } else {
                            $_db->where($key,'>',substr($value,1));
                        }

                    } else if(isset($value[0]) && $value[0] == '<') {
                        if(isset($value[1]) && $value[1] == '=') {
                            $_db->where($key,'<=',substr($value,2));
                        } else {
                            $_db->where($key,'<',substr($value,1));
                        }

                    } else if(isset($value[0]) && $value[0] == '=') {
                        $_db->where($key,'=',substr($value,1));

                    } else {
                        $_db->where($key,'like','%'.$value.'%');
                    }
                }
            }


            // 3.3 Sort
            if(isset($actions['table']['sort'])) {
                foreach($actions['table']['sort'] as $key => $value) {
                    $_db->orderBy($key, $value);
                }
            }

            if (empty($this->sort)) {
                $_db->orderBy('id',"desc");
            } else {
                foreach($this->sort as $key => $value) {
                    $_db->orderBy($key, $value);
                }
            }


            //  3.4 최종 데이터 읽기
            //  페이징이 없는 경우, 전체 읽기
            if(isset($this->paging) && is_numeric($this->paging) ) {
                $rows = $this->_db->paginate($this->paging);
            } else {
                $rows = $this->_db->get();
            }

            //  3.5
            $this->setData($rows);
            $this->setIds();

            // session()->flash('message',"데이터...");
            return $rows;
        }

        // 데이터 없음
        return [];
    }


    public function setWhere($arr)
    {
        if(isset($this->actions['where'])) {
            if(is_array($this->actions['where'])) {
                // 추가
                $this->actions['where'] []= $arr;
                return $this;
            }
        }

        // 초기화
        $this->actions['where'] = $arr;
        return $this;
    }


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
        //dd($key);
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


    public function popupClose()
    {
        $this->popupForm = false;
        $this->forms = [];
    }
}
