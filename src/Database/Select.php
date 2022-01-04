<?php

namespace Jiny\Table\Database;
use Illuminate\Support\Facades\DB;
class Select
{
    public $DB;
    public function __construct($table)
    {
        $this->DB = DB::table($table);
    }

    public function wheres($wheres)
    {
        foreach ($wheres as $key => $where) {
            if(is_array($where)) {
                foreach($where as $t => $v) {
                    $this->DB->where($key, $t, $v);
                }
            } else {
                $this->DB->where($key,$where);
            }
        }
    }

    public function filters($filters)
    {
        foreach ($filters as $key => $filter) {
            $this->DB->where($key,'like','%'.$filter.'%');
        }
    }

    public function sort($sort)
    {
        if (empty($sort)) {
            $this->DB->orderBy('id',"desc");
        } else {
            foreach($sort as $key => $value) {
                $this->DB->orderBy($key, $value);
            }
        }
    }

    public function get()
    {
        return $this->DB->get();
    }

    public function paginate($paging)
    {
        return $this->DB->paginate($paging);
    }


}
