<?php

namespace Jiny\Table\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

class TableController extends Controller
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

    protected static $Instance;
    protected $wire;
    public static function getInstance($wire)
    {
        self::$Instance->wire = $wire;
        return self::$Instance;
    }

    public function index(Request $request)
    {
        return view("jinytable::table",[
            'actions'=>$this->actions
        ]);
    }

    public function create()
    {
        return view("jinytable::edit",[
            'actions'=>$this->actions
        ]);
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $this->actions['id'] = $id;
        return view("jinytable::edit",['actions'=>$this->actions]);

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id, Request $request)
    {

    }

    /**
     * delete 선택한 항목 삭제
     *
     * @param  mixed $request
     * @return void
     */
    public function delete(Request $request)
    {
        $ids = $request->ids;
        // 선택한 항목 삭제 AJAX
        DB::table($this->tablename)->whereIn('id', $ids)->delete();
        return response()->json(['status'=>"200", 'ids'=>$ids]);
    }

}
