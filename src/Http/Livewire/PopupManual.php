<?php

namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class PopupManual extends Component
{
    /**
     * LivePopupManual with AlpineJS
     */
    public function render()
    {
        return view("jinytable::popup-manual");
    }

    /**
     * 팝업창 관리
     */
    protected $listeners = ['popupManualOpen','popupManualClose'];

    public $popupManual = false;

    public function popupManualOpen()
    {
        $this->popupManual = true;
    }

    public function popupManualClose()
    {
        $this->popupManual = false;
    }

}
