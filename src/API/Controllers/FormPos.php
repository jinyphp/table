<?php

namespace Jiny\Table\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use \Jiny\Html\CTag;


class FormPos extends Controller
{
    public function index()
    {
        $i=0;
        foreach($_POST['pos'] as $id => $tab) {
            $i++;
            if($id == 999) {
                continue;
            } else {
                // 순서이동, 탭 변경
                DB::table('table_forms')
                    ->where('id', $id)
                    ->update(['pos'=>$i, 'tab'=>$tab]);
            }
        }

        return response()->json([
            'post'=>$_POST
        ]);
    }

}
