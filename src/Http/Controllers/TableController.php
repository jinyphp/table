<?php
/**
 * Action 동작을 위한 공용 Logic
 */
namespace Jiny\Table\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Jiny\Table\Http\Controllers\ResourceController;
class TableController extends ResourceController
{
    use \Jiny\Table\Http\Controllers\SetMenu;

    //const MENU_PATH = "menus";
    public function __construct()
    {
        parent::__construct();  // setting Rule 초기화
        $this->setVisit($this); // Livewire와 양방향 의존성 주입

        // 메뉴 설정
        $user = Auth::user();
        $this->setUserMenu($user);
    }
}
