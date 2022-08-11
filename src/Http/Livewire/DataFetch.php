<?php
/**
 * Hook를 검색 처리합니다.
 */
namespace Jiny\Table\Http\Livewire;

trait DataFetch
{
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

            // 3.1 제한조건 적용
            if(isset($this->actions['where']) && is_array($this->actions['where'])) {
                $this->dbSelect->wheres($this->actions['where']);
            }


            // 3.2 사용자필터 조건적용
            foreach ($this->filter as $key => $filter) {
                $DB->where($key,'like','%'.$filter.'%');
            }


            // 3.3 Sort
            if (empty($this->sort)) {
                $DB->orderBy('id',"desc");
            } else {
                foreach($this->sort as $key => $value) {
                    $DB->orderBy($key, $value);
                }
            }


            //  3.4 최종 데이터 읽기
            //  페이징이 없는 경우, 전체 읽기
            if(isset($this->paging) && is_numeric($this->paging) ) {
                $rows = $this->dbSelect->paginate($this->paging);
            } else {
                $rows = $this->dbSelect->get();
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
