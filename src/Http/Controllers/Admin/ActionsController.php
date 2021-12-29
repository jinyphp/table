<?php

namespace Jiny\Table\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActionsController extends Controller
{
    protected $actions = [];
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $path = resource_path('actions');

        $files = [];
        foreach (scandir($path) as $item) {
            if($item == "." || $item == "..") continue;
            $files []= $item;
        }

        return view("jinytable::admin.actions.main",['files'=>$files]);
    }

    public function create()
    {

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

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id, Request $request)
    {

    }

}
