<?php

namespace Jiny\Table\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
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
