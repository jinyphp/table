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

use Jiny\Table\Http\Controllers\BaseController;
class AdminController extends BaseController
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
        return $this->home($request);
    }


    public function home(Request $request, $args=[])
    {
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 메뉴 설정
        //$user = Auth::user();
        //$this->setUserMenu($user);
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
                    //dump($this->actions['view_main']);
                    // Menus::admin.menu_item.main
                    $view = "jinytable::main";
                }
            } else {
                $view = "jinytable::main";
            }
            //$view = "Menus::admin.menu_item.main";
            //dd($this->actions['view_main']);

            // view 전달 데이터 정리
            $data = [];
            $data['actions']=$this->actions;
            $data['request']=$request;

            foreach($args as $key => $item) {
                $data[$key] = $item;
            }

            return view($view, $data);
        }



        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }







    public function show(Request $request, $id)
    {
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 메뉴 설정
        $user = Auth::user();
        $this->setUserMenu($user);

        // 권한
        $this->permitCheck();
        if($this->permit['read']) {

        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function create(Request $request)
    {
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 메뉴 설정
        $user = Auth::user();
        $this->setUserMenu($user);

        // 권한
        $this->permitCheck();
        if($this->permit['create']) {

            // 메인뷰 페이지...
            if (isset($this->actions['view_edit'])) {
                $view = $this->actions['view_edit'];
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
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 권한
        $this->permitCheck();
        if($this->permit['create']) {


        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);

    }

    public function edit(Request $request, $id)
    {
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 메뉴 설정
        $user = Auth::user();
        $this->setUserMenu($user);

        // 권한
        $this->permitCheck();
        if($this->permit['update']) {
            // 마지막 값이, id로 간주합니다.
            $keyId = array_key_last($this->actions['nesteds']);
            $this->actions['id'] = $this->actions['nesteds'][$keyId];

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
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 권한
        $this->permitCheck();
        if($this->permit['update']) {


        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions,
            'request'=>$request
        ]);
    }

    public function destroy($id, Request $request)
    {
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 권한
        $this->permitCheck();
        if($this->permit['delete']) {
            // 마지막 값이, id로 간주합니다.
            $keyId = array_key_last($this->actions['nesteds']);
            $this->actions['id'] = $this->actions['nesteds'][$keyId];
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
        $this->checkRequestNesteds($request);
        $this->checkRequestQuery($request);

        // 권한
        $this->permitCheck();
        if($this->permit['delete']) {

            $ids = $request->ids;
            // 선택한 항목 삭제 AJAX
            DB::table($this->tablename)->whereIn('id', $ids)->delete();
            return response()->json(['status'=>"200", 'ids'=>$ids]);

        }

        // 권한 접속 실패
        return response()->json(['status'=>"201",'message'=>"권한 설정없음"]);
    }

}
