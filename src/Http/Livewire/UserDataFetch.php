<?php
/**
 * Hook를 검색 처리합니다.
 */
namespace Jiny\Table\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait UserDataFetch
{
    /** ----- ----- ----- ----- -----
     *  Read Data
     */
    protected $db;
    public $dataType = "table";
    protected $dbSelect;
    public $wheres = [];

    public function setTable($table)
    {
        // 외부 별도 클래스로 처리
        $this->dbSelect = new \Jiny\Table\Database\Select($table);

        $this->db = DB::table($table);
        return $this;
    }

    public function database()
    {
        return $this->db;
    }


    public function DB()
    {
        return $this->db;
    }

    ## 사용자 정보의 데이터만 읽어 옵니다.
    public function getUserRelation($id=null, $relation=null)
    {
        if($id) {
            // 사용자 지정한 userid를 사용
        } else {
            // 현재 로그인한 userid를 검색
            $user = Auth::user();
            $id = $user->id;
        }

        if($relation) {
            // M:N relation 으로 검색
            $ids = userRelation($relation, $id);
            $this->db->whereIn('id',$ids)->get();

        } else {
            $this->db->where('user_id', $id);
        }

        return $this;
    }

    protected function dataFetch($actions)
    {
        if($this->db) {


            // 3.1 제한조건 적용
            if(isset($this->actions['where']) && is_array($this->actions['where'])) {
                $this->wheres($this->actions['where']);
            }

            // 3.2 사용자필터 조건적용
            foreach ($this->filter as $key => $filter) {
                $this->db->where($key,'like','%'.$filter.'%');
            }


            // 3.3 Sort
            if (empty($this->sort)) {
                $this->db->orderBy('id',"desc");
            } else {
                foreach($this->sort as $key => $value) {
                    $this->db->orderBy($key, $value);
                }
            }


            //  3.4 최종 데이터 읽기
            //  페이징이 없는 경우, 전체 읽기
            if(isset($this->paging) && is_numeric($this->paging) ) {
                $rows = $this->db->paginate($this->paging);
            } else {
                $rows = $this->db->get();
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

    public function wheres($wheres)
    {
        foreach ($wheres as $key => $where) {
            if(is_array($where)) {
                foreach($where as $t => $v) {
                    $this->db->where($key, $t, $v);
                }
            } else {
                $this->db->where($key, $where);
            }
        }

        return $this;
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

}
