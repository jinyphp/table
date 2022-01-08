<?php
/**
 *
 */
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;

class TableTitle extends Component
{
    public $actions;
    public function render()
    {


        if (isset($actions['view_title']) && !empty($actions['view_title'])) {
            if(view()->exists($actions['view_title'])) {
                return view($actions['view_title']);
            }
        }

        return view("jinytable::title");
    }
}
