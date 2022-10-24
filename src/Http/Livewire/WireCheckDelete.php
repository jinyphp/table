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

class WireCheckDelete extends Component
{
    public $actions;
    public $selected = [];

    public function render()
    {
        return view("jinytable::livewire.checkdelete");
    }

    protected $listeners = ['deleteCheck'];

    public function deleteCheck($ids)
    {

    }
}
