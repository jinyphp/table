<?php

namespace Jiny\Table\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use \Jiny\Html\CTag;

use Jiny\Table\Http\Controllers\ResourceController;
class Forms extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);
    }

    public function hookIndexed($wire, $rows)
    {


        return $rows;
    }

}
