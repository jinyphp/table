<?php

namespace Jiny\Table\Http\Controllers\Modal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class Popup extends Controller
{
    // 리소스 저장경로
    const PATH = "actions";
    protected $actions = [];

    ## json 파일을 확인하고, 읽기
    private function readJsonAction($path)
    {
        $filename = $path.DIRECTORY_SEPARATOR;
        $filename .= str_replace("/","_",$this->actions['route']['uri']).".json";
        //dump($filename);
        if (file_exists($filename)) {
            $json = file_get_contents($filename);
            return json_decode($json, true);
        }

        return [];
    }


    public function create(Request $request)
    {
        $this->actions['route']['uri'] = trim($request->action, '/');
        $path = resource_path("actions");
        foreach ($this->readJsonAction($path) as $key => $value)
        {
            // Json Actions 정보를 반영
            $this->actions[$key] = $value;
        }

        return view("jinytable::modal.create",['actions'=>$this->actions]);


    }
}
