<?php

namespace Jiny\Table;

use Illuminate\Support\Facades\Route;

class ActionRule
{
    public $routename;

    ## 테이블 정보
    public $table;

    public $filter;
    public $list;
    public $form;

    public function __construct()
    {
        $routename = Route::currentRouteName();
        $this-> routename = substr($routename,0,strrpos($routename,'.'));
    }

    public function setTable($table)
    {
        $this->table = $table;
    }


}
