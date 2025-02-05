<?php
namespace Jiny\Table\Http\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

use Livewire\WithFileUploads;
use Livewire\Attributes\On;

/**
 * 관리자 Livewire Table
 */
class AdminTable extends Component
{
    use WithFileUploads;
    use WithPagination;
    
    use \Jiny\WireTable\Http\Trait\Hook;
    use \Jiny\WireTable\Http\Trait\Permit;
    use \Jiny\WireTable\Http\Trait\UploadSlot;

    public $actions;
    public $paging = 5;
    public $admin_prefix;
    public $message;

    // 추출된 데이터 목록 (array)
    public $data=[];
    public $table_columns=[];

    //public $_id;

    public $popupWindowWidth = "4xl";

    // 테이블
    public $tablename;

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

        // 페이징 초기화
        if (isset($this->actions['paging'])) {
            $this->paging = $this->actions['paging'];
        }

        // 테이블 목록
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
        $this->viewFile = "jiny-table"."::admin.table.layout";
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
    private function getViewMainLayout()
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
        $this->viewTable = "jiny-table"."::admin.table.layout";
        return $this->viewTable;
    }


    protected $listeners = ['refeshTable'];
    #[On('refeshTable')]
    public function refeshTable()
    {
        // 페이지를 재갱신 합니다.
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

            // DB 검색조건 적용
            if(isset($this->actions['table']['where'])) {
                $this->checkWhere();
            }
            
            

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


    private function checkWhere()   
    {
        if(is_array($this->actions['table']['where'])) {
            foreach($this->actions['table']['where'] as $key => $value) {
                //dd($key, $value);
                if(!is_numeric($key)) {
                    $this->_db->where($key, $value);
                }
            }
        }
        
        return $this;
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

    /** ----- ----- ----- ----- -----
     *  checkBox Selecting
     */
    public $selectedall = false;
    public $selected = [];
    public $selected_count = 0;


    // model.live로 selectedall 클릭시 호출됩니다.
    public function updatedSelectedall($value)
    {
        if($value) {
            $this->selected = []; // 초기화

            // 전체 선택 체크, id값 지정
            foreach($this->ids as $i => $v) {
                $this->selected[$i] = strval($v);
            }

        } else {
            // 모든 선택 해제
            $this->selected = [];
        }

        // 선택된 true 갯수 확인
        $this->selected_count = count($this->selected);
        if($this->selected_count == 0) {
            $this->popupCheckDeleteClose();
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

        // 선택된 true 갯수 확인
        $this->selected_count = count($this->selected);
        if($this->selected_count == 0) {
            $this->popupCheckDeleteClose();
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
    public $checkDelete = false;
    public $checkDeleteConfirm = false;

    public function popupCheckDelete()
    {
        if($this->permit['delete']) {
            $this->checkDelete = true;
        } else {
            $this->popupPermitOpen();
        }
    }

    public function popupCheckDeleteClose()
    {
        // 삭제 확인창을 닫기
        $this->checkDelete = false;
        $this->checkDeleteConfirm = false;
    }

    public function checkeDeleteConfirm()
    {
        $this->checkDeleteConfirm = true;
    }

    public function checkeDeleteRun()
    {
        if($this->permit['delete']) {

            // 1.컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCheckDeleting")) {
                if(method_exists($controller, "hookCheckDeleting")) {
                    $controller->hookCheckDeleting($this, $this->selected);
                }
            }

            // 3.복수의 ids를 삭제합니다.
            DB::table($this->tablename)
                ->whereIn('id', $this->selected)->delete();

            // 4. 컨트롤러 메서드 호출
            if ($controller = $this->isHook("hookCheckDeleted")) {
                if(method_exists($controller, "hookCheckDeleted")) {
                    $controller->hookCheckDeleted($this, $this->selected);
                }
            }

            // 5. 기존에 선택된 체크박스는 초기화 함.
            $this->selectedall = false;
            $this->selected = [];
            $this->selected_count = 0;

            // Livewire Table을 갱신을 호출합니다.
            $this->dispatch('refeshTable');

            $this->popupCheckDeleteClose();

        } else {
            $this->popupPermitOpen();
        }
    }


    /**
     * 라이브와이어 이벤트
     */
    public function create()
    {
        $this->dispatch('createPopupForm');
    }

    public function edit($id)
    {
        $this->dispatch('editPopupForm', $id);
    }
}
