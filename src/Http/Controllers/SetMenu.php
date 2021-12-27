<?php
namespace Jiny\Table\Http\Controllers;

trait SetMenu
{
    private $MENU_PATH = "menus";

    protected function setUserMenu($user)
    {
        if(isset($user->menu)) {
            ## 사용자 지정메뉴 우선설정
            xMenu()->setPath($user->menu);
        } else {
            ## 설정에서 적용한 메뉴
            if(isset($this->actions['menu'])) {
                $menuid = _getKey($this->actions['menu']);
                xMenu()->setPath($this->MENU_PATH . DIRECTORY_SEPARATOR . $menuid . ".json");
            }
        }
    }
}
