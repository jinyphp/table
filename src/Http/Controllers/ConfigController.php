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
    // 리소스 저장경로
    const PATH = "actions";
    protected $actions = [];

    public function __construct()
    {
        ## 라우트정보
        $routename = Route::currentRouteName();
        $uri = Route::current()->uri;

        $this->actions['route']['uri'] = $uri;
        $this->actions['routename'] = substr($routename,0,strrpos($routename,'.'));
        $this->actions['route']['name'] = $this->actions['routename'];

        $conf = config("jiny_table.path");
        $path = resource_path( $conf['path'] ?? self::PATH);
        foreach ($this->readJsonAction($path) as $key => $value) {
            $this->actions[$key] = $value;
        }
    }

    private function readJsonAction($path)
    {
        $filename = $path.DIRECTORY_SEPARATOR.str_replace("/","_",$this->actions['route']['uri']).".json";
        if (file_exists($filename)) {
            return json_decode(file_get_contents($filename), true);
        }

        return [];
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
