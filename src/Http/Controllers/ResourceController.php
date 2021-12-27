<?php
/**
 * CRUD 처리를 위한 리소스 컨트롤러
 */
namespace Jiny\Table\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
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


    /**
     * CRUD Resource Process
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "read")) {

            // 메인뷰 페이지...
            if (isset($this->actions['view_main'])) {
                if (view()->exists($this->actions['view_main']))
                {
                    $view = $this->actions['view_main'];
                } else {
                    $view = "jinytable::main";
                }
            } else {
                $view = "jinytable::main";
            }

            return view($view,[
                'actions'=>$this->actions,
                'request'=>$request
            ]);
        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "delete")) {

        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "create")) {
            // 메인뷰 페이지...
            if (isset($this->actions['view_edit'])) {
                $view = $this->actions['view_edit'];
                //dd($view);
            } else {
                $view = "jinytable::edit";
            }

            return view($view,[
                'actions'=>$this->actions
            ]);
        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "create")) {

        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);

    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "update")) {
            $this->actions['id'] = $id;
            return view("jinytable::edit",['actions'=>$this->actions]);
        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "update")) {

        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function destroy($id, Request $request)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "delete")) {

        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    /**
     * delete 선택한 항목 삭제
     *
     * @param  mixed $request
     * @return void
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        $Role = new \Jiny\Auth\Roles($user->id);
        if ($Role->permit($this->actions, "delete")) {
            $ids = $request->ids;
            // 선택한 항목 삭제 AJAX
            DB::table($this->tablename)->whereIn('id', $ids)->delete();
            return response()->json(['status'=>"200", 'ids'=>$ids]);
        }

        // 권한 접속 실패
        return response()->json(['status'=>"201",'message'=>"권한 설정없음"]);
    }

}
