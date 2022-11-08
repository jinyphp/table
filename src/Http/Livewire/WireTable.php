<?php
/**
 *
 */
namespace Jiny\Table\Http\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class WireTable extends Component
{
    use WithPagination;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Livewire\CheckDelete;
    use \Jiny\Table\Http\Livewire\DataFetch;

    public $actions;
    public $paging = 10;
    public function mount()
    {
        $this->permitCheck();

        // 페이징 초기화
        if (isset($this->actions['paging'])) {
            $this->paging = $this->actions['paging'];
        }
    }


    /** ----- ----- ----- ----- -----
     *  Table
     */
    public function render()
    {
        // 1. 데이터 테이블 체크
        if(isset($this->actions['table']) && $this->actions['table']) {
            $this->setTable($this->actions['table']);
        } else {
            // 테이블명이 없는 경우
            return view("jinytable::error.tablename_none");
        }


        // 2. 후킹 :: 컨트롤러 메서드 호출
        if ($controller = $this->isHook("HookIndexing")) {
            $result = $controller->HookIndexing($this);
            if($result) {
                // 반환값이 있는 경우, 출력하고 이후동작을 중단함.
                return $result;
            }
        }

        // 3. 데이터를 읽어 옵니다.
        $rows = $this->dataFetch($this->actions);


        // 4. 후크 :: 읽어온 데이터를 후작업 합니다.
        if($rows) {
            if ($controller = $this->isHook("HookIndexed")) {
                $rows = $controller->HookIndexed($this, $rows);

                if(is_null($rows)) {
                    return view("jinytable::error.message",[
                        'message'=>"HookIndexed() 호출 반환값이 없습니다."
                    ]);
                }
            }
        }

        return view($this->getLayoutView(), [
            'rows'=>$rows,
            'popupEdit'=>$this->editPopupFunc(),
            'editLink'=>$this->editLinkFunc()
        ]);
    }

    // 5. 내부함수 생성
    // 팝업창 폼을 활성화 합니다.
    private function editPopupFunc()
    {
        $funcEditPopup = function ($item, $title)
        {
            $link = xLink($title)->setHref("javascript: void(0);");
            $link->setAttribute("wire:click", "$"."emit('popupEdit','".$item->id."')");

            if (isset($item->enable) && $item->enable) {
                return $link;
            } else if (isset($item->_enable) && $item->_enable) {
                return $link;
            } else {
                return xSpan($link)->style("text-decoration:line-through;");
            }

            return $link;
        };

        return $funcEditPopup;
    }

    // 내부함수 생성
    // form 페이지로 url을 이동합니다.
    private function editLinkFunc()
    {
        $rules = $this->actions;
        $funcEditLink = function ($item, $title) use ($rules)
        {
            $link = xLink($title)->setHref(route($rules['routename'].".edit", $item->id));
            if($item->enable) {
                return $link;
            } else {
                return xSpan($link)->style("text-decoration:line-through;");
            }
            return $link;
        };
        return $funcEditLink;
    }


    private function getLayoutView()
    {
        // 기본값
        $view = "jinytable::livewire.table";

        // 사용자값
        if(isset($this->actions['view_main_layout'])) {
            if($this->actions['view_main_layout']) {
                $view = $this->actions['view_main_layout'];
            }            
        }

        return $view;
    }


    /* ----- ----- ----- ----- ----- */

    protected $listeners = ['refeshTable','refeshDelete'];
    public function refeshTable()
    {
        // 페이지를 재갱신 합니다.
    }

    public function refeshDelete()
    {
        // 삭제후 페이지를 재갱신 합니다.
    }

    public function edit($id)
    {
        $this->emit('popupEdit',$id);
    }


    /**
     *
     */
    public function call($method, ...$args)
    {
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, $method)) {
                return $controller->$method($this, $args);
            }
        }
    }


    public function columnHidden($col_id)
    {
        $row = DB::table('table_columns')->where('id',$col_id)->first();
        if($row->display) {
            DB::table('table_columns')->where('id',$col_id)->update(['display'=>""]);
        } else {
            DB::table('table_columns')->where('id',$col_id)->update(['display'=>"true"]);
        }
    }

}
