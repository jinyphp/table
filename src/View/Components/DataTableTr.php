<?php

namespace Jiny\Table\View\Components;

use Illuminate\View\Component;

class DataTableTr extends Component
{
    public $item;
    public $selected;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $selected)
    {
        $this->item = $item;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('jinytable::components.datatable-tr',['item'=>$this->item, 'selected'=>$this->selected]);
    }
}
