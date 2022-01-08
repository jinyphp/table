<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Test extends Component
{
    use WithFileUploads;
    use \Jiny\Table\Http\Livewire\Hook;
    use \Jiny\Table\Http\Livewire\Permit;

    public $forms=[];
    public function render()
    {

        return <<<'blade'
        <div>
        <input wire:model="forms.sort">
        </div>
    blade;
    }


}
