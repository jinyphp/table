<?php
namespace Jiny\Table\Http\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class WireDetail extends Component
{
    use \Jiny\Table\Http\Livewire\Hook;

    public $actions;
    public $data;

    public $route;

    public function mount()
    {
        $this->route = url()->previous();
    }

    public function render()
    {
        // 1. 데이터 테이블 체크
        if(isset($this->actions['table']) && $this->actions['table']) {

        } else {
            // 테이블명이 없는 경우
            return view("jinytable::error.tablename_none");
        }

        // 2. 후킹 :: 컨트롤러 메서드 호출
        if ($controller = $this->isHook("HookDetail")) {
            $result = $controller->HookDetail($this);
            if($result) {
                // 반환값이 있는 경우, 출력하고 이후동작을 중단함.
                return $result;
            }
        }


        $row = DB::table($this->actions['table'])->where('id',$this->actions['id'])->first();
        if($row) {
            $this->data = [];
            foreach($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }

        // 4. 후크 :: 읽어온 데이터를 후작업 합니다.
        if($row) {
            if ($controller = $this->isHook("HookDetailed")) {
                $row = $controller->HookDetailed($this, $row);

                if(is_null($row)) {
                    return view("jinytable::error.message",[
                        'message'=>"HookDetailed() 호출 반환값이 없습니다."
                    ]);
                }
            }
        }

        return view($this->actions['view_detail'],[
            'rows'=>$row,
        ]);
    }

    protected $listeners = ['refeshTable','refeshDelete'];
    public function refeshTable()
    {
        // 페이지를 재갱신 합니다.
    }

    public function refeshDelete()
    {
        // 이전 페이지로 이동
        redirect($this->route);
    }

    public function popupEdit()
    {
        $this->emit('popupEdit',$this->actions['id']);
    }
}