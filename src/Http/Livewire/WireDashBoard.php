<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class WireDashBoard extends Component
{
    use WithPagination;
    public $controller;
    public $actions;

    public function render()
    {
        $funcViewPopup = function ($item, $title)
        {
            $link = xLink($title)->setHref("javascript: void(0);");
            $link->setAttribute("wire:click", "view('".$item->id."')");

            if($item->enable) {
                return $link;
            } else {
                return xSpan($link)->style("text-decoration:line-through;");
            }
            return $link;
        };

        // 컨트롤러 메서드 호출
        if(isset($this->controller)) {
            $ctrl = new $this->controller;
            $ctrl->wire = $this;

            $this->actions = $ctrl->getActions();

            if(method_exists($ctrl, "dashboard")) {
                return $ctrl->dashboard()
                    ->with('popupView', $funcViewPopup);
            }
        }

        return <<<'blade'
        <div >
            none
        </div>
    blade;
    }


    /**
     * 팝업창 관리
     */
    public $popupDashView = false;
    public function popupViewOpen()
    {
        $this->popupDashView = true;
    }

    public function popupViewClose()
    {
        $this->popupDashView = false;
    }

    public $form;
    public function view($id)
    {
        $this->popupViewOpen();
        $row = DB::table($this->actions['table'])->find($id);
        $this->setForm($row);
    }

    private function setForm($row)
    {
        foreach ($row as $key => $value) {
            $this->form[$key] = $value;
        }
    }

}
