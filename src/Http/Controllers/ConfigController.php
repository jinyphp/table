<?php
/**
 *
 */
namespace Jiny\Table\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    protected $actions = [];

    public function __construct()
    {
        ## 라우트이름
        $routename = Route::currentRouteName();
        $this->actions['routename'] = substr($routename,0,strrpos($routename,'.'));
    }

    protected function setVisit($obj)
    {
        $this->actions['controller'] = $obj::class;
        self::$Instance = $obj;
    }

    public function getActions()
    {
        return $this->actions;
    }

    protected static $Instance;
    public $wire;
    public static function getInstance($wire)
    {
        self::$Instance->wire = $wire;
        return self::$Instance;
    }


    public function index(Request $request)
    {
        // 메인뷰 페이지...
        if (isset($this->actions['view_main'])) {
            $view = $this->actions['view_main'];
        } else {
            $view = "jinytable::config";
        }

        return view($view,[
            'actions'=>$this->actions
        ]);
    }



}
