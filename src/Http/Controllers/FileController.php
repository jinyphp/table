<?php
/**
 * File 처리를 위한 리소스 컨트롤러
 */
namespace Jiny\Table\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Jiny\Table\Http\Controllers\BaseController;
class FileController extends BaseController
{
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Controllers\SetMenu;

    private function checkRequestNesteds($request)
    {
        if (isset($this->actions['nesteds'])) {
            foreach($this->actions['nesteds'] as $i => $nested) {
                if(isset($request->$nested)) {
                    unset($this->actions['nesteds'][$i]);
                    $this->actions['nesteds'][$nested] = $request->$nested;
                    $this->actions['request']['nesteds'][$nested] = $request->$nested;
                }
            }
        }

        return $this;
    }

    // Request에서 전달된 query 스트링값을 저장합니다.
    private function checkRequestQuery($request)
    {
        if($request->query) {
            foreach($request->query as $key => $q) {
                $this->actions['request']['query'][$key] = $q;
            }
        }
        return $this;
    }


    /**
     * CRUD Resource Process
     */
    public function index(Request $request)
    {
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);


        // 메뉴 설정
        /*
        $user = Auth::user();
        $this->setUserMenu($user);
        */
        $this->menu_init();


        // 권한
        $this->permitCheck();
        if($this->permit['read']) {

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

}
