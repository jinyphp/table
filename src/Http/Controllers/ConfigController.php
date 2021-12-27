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
    use \Jiny\Table\Http\Controllers\SetMenu;

    // 리소스 저장경로
    const PATH = "actions";
    //const MENU_PATH = "menus";
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

    /*
    protected function setUserMenu($user)
    {
        if(isset($user->menu)) {
            ## 사용자 지정메뉴 우선설정
            xMenu()->setPath($user->menu);
        } else {
            ## 설정에서 적용한 메뉴
            if(isset($this->actions['menu'])) {
                $menuid = _getKey($this->actions['menu']);
                xMenu()->setPath(self::MENU_PATH.DIRECTORY_SEPARATOR.$menuid.".json");
            }
        }
    }
    */

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
        // 메뉴 설정
        $user = Auth::user();
        $this->setUserMenu($user);

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
