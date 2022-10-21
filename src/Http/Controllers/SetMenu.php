<?php
namespace Jiny\Table\Http\Controllers;

use Illuminate\Support\Facades\Auth;

trait SetMenu
{
    private $MENU_PATH = "menus";

    protected function menu_init($code=null)
    {
        if(function_exists('xMenu')) {
            $Menu = xMenu();
            $menu_id = $Menu->getMenuId();
            if($menu_id) {
                // 이전에 메뉴가 설정되어 있는 경우 초기화 취소
                
            } else {
                // 우선순위1. 사용자 메뉴
                $user = Auth::user();
                if($user) {
                    $menu_id = $this->setUserMenu($user);
                    if($menu_id) {
                        return true;
                    }
                }

                if($code) {
                    //우선순위2. 지정한 메뉴
                    $this->setMenu($code);
                } else {
                    //우선순위3. Action 메뉴
                    $this->actionMenu();
                }
            }
        }
    }

    protected function setMenu($code=null)
    {
        if(function_exists('xMenu')) {
            $Menu = xMenu();

            if($code) {
                // 지정한 메뉴로 설정
                if(is_numeric($code)) {
                    $Menu->setMenuId($code);           
                } else {
                    $Menu->setMenuCode($code);                            
                }

                $menuId = $code;
                $Menu->setPath($this->MENU_PATH . DIRECTORY_SEPARATOR . $menuId . ".json");

            } else {
                $this->actionMenu();
            }
            
            return $Menu;
        }
    }


    protected function actionMenu()
    {
        if(function_exists('xMenu')) {
            $Menu = xMenu();

            // 메뉴값이 없는 경우 actions 정보를 참조
            $menuId = _getKey($this->actions['menu']); 
            $Menu->setPath($this->MENU_PATH . DIRECTORY_SEPARATOR . $menuId . ".json");

            return $menuId;
        }
    }

    protected function setUserMenu($user)
    {
        if(function_exists('xMenu')) {
            $Menu = xMenu();

            // 사용자가 있는 경우 사용자 메뉴 적용
            if(isset($user->menu)) {

                if(is_numeric($user->menu)) {
                    $Menu->setMenuId($user->menu);           
                } else {
                    $Menu->setMenuCode($user->menu);
                }

                $Menu->setPath($user->menu);

                return $user->menu;
            }
        }

        return false;
    }

}
