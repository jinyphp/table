<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Table\Http\Livewire;
use Livewire\Component;

class DesignForm extends Component
{


    public function render()
    {
        return view('jinytable::livewire.design.form');
    }

    protected $listeners = ['popupDesignOpen','popupDesignClose','popupDesignCreate'];
    public $popupDesgin = false;

    public function popupDesignOpen()
    {
        $this->popupDesgin = true;
    }

    public function popupDesignClose()
    {
        $this->popupDesgin = false;
    }

    public function popupDesignCreate($actions,...$args)
    {
        $this->popupDesignOpen();
        $aaa = [$actions, $args];
        dd($aaa);
    }

}
