<?php
namespace Jiny\Table\Http\Livewire;

use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

use Livewire\WithFileUploads;
use Livewire\Attributes\On;


/**
 * create disable table
 */
use Jiny\Table\Http\Livewire\AdminTable;
class AdminTableNone extends AdminTable
{
    public function mount()
    {
        parent::mount();

        $this->actions['create']['enable'] = false;
    }
}
