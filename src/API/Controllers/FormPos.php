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

        foreach($_POST['pos'] as $id => $pos) {
            DB::table('form_tabs')->where('id', $id)->update(['pos'=>$pos]);

        }

        /*
        $rows = DB::table('form_tabs')
            ->orderby('pos',"asc")->get();
        */
        return response()->json([
            'post'=>$_POST
        ]);
    }

}
