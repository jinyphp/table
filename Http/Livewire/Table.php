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
 * 테이블 전체 출력
 */
class Table extends Component
{
    use WithPagination;
    use \Jiny\WireTable\Http\Trait\Permit;
    use \Jiny\WireTable\Http\Trait\Hook;

    public $actions;

    // 테이블
    public $tablename;
    public $admin_prefix;
    public $message;

    // 추출된 데이터 목록 (array)
    public $data=[];
    public $table_columns=[];

    // 화면
    public $viewFile, $viewTable, $viewList;

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

        if(!$this->viewFile) {
            $this->viewFile = "jiny-table"."::table.basic.layout";
        }

        // 목록 뷰 파일 지정
        if(!$this->viewList) {
            if(isset($this->actions['view']['list'])) {
                $this->viewList = $this->actions['view']['list'];
            }
        }

    }


    /**
     * 목록 출력
     */
    public function render()
    {
        //dd("eee");
        // 1. 데이터 테이블 체크
        if(!$this->tablename) {
            // 테이블명이 없는 경우
            return view("jiny-table::table.error",[
                'message'=>"테이블명이 지정되어 있지 않습니다."
            ]);
        }


        // 2. 후킹_before :: 컨트롤러 메서드 호출
        // DB 데이터를 조회하는 방법들을 변경하려고 할때 유용합니다.
        if ($controller = $this->isHook("HookIndexing")) {
            $result = $this->controller->hookIndexing($this);
            if($result) {
                // 반환값이 있는 경우, 출력하고 이후동작을 중단함.
                return view("jiny-table::table.error",[
                    'message'=>"HookIndexing() 호출 반환값이 있습니다. : ".$result
                ]);
            }
        }


        // 3. DB에서 데이터를 읽어 옵니다.
        $this->setTable($this->tablename);
        $rows = $this->dataFetch($this->actions);
        //dd($rows);



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
                    return view("jiny-table::table.error",[
                        'message'=>$message
                    ]);
                }
            }
        }

        $this->toData($rows); // rows를 data 배열에 복사해 둡니다.


        // 6. 출력 레이아아웃
        $this->viewTable = $this->getViewMainLayout();
        return view($this->viewFile,[
            'rows'=>$rows
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
    private function getViewMainLayout($default=null)
    {
        if($this->viewTable) {
            return $this->viewTable;
        }

        // 사용자가 지정한 table 레이아웃이 있는 경우 적용!
        if(isset($this->actions['view']['table'])) {
            if($this->actions['view']['table']) {
                $this->viewTable = $this->actions['view']['table'];
                return $this->actions['view']['table'];
            }
        }

        if($default) {
            // 기본값
            $this->viewTable = $default;
            return $default;
        }

        return false;
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
                        if(!is_numeric($key)) {
                            $this->_db->where($key, $value);
                        }
                    }
                }
            }
            if(isset($actions['table']['where'])) {
                if(is_array($actions['table']['where'])) {
                    foreach($actions['table']['where'] as $key => $value) {
                        if(!is_numeric($key)) {
                            $this->_db->where($key, $value);
                        }
                    }
                }
            }



            // 3.3 정렬(sort)
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
            $rows = $this->_db->get();

            //  3.5
            $this->setData($rows);
            $this->setIds();

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




}
