<?php
/**
 * Action 동작을 위한 공용 Logic
 */
namespace Jiny\Table\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Jiny\Table\Http\Controllers\ResourceController;
class TableController extends ResourceController
{

    public function __construct()
    {
        parent::__construct();  // setting Rule 초기화
        $this->setVisit($this); // Livewire와 양방향 의존성 주입
    }
}
