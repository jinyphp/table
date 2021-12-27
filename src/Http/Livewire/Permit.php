<?php
/**
 * Permit  처리합니다.
 */
namespace Jiny\Table\Http\Livewire;

use Illuminate\Support\Facades\Auth;

trait Permit
{
    public $permit;
    public $popupPermit = false;

    public function permitCheck()
    {
        $user = Auth::user();
        if (function_exists("authRoles")) {
            $Role = authRoles($user->id);
            //$Role = new \Jiny\Auth\Roles($user->id);
            $this->permit = $Role->permitAll($this->actions);
        } else {
            // 모듈이 설치되어 있지 않는 경우, 모두 허용
            $this->permit = [
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
            ];
        }
    }

    public function popupPermitOpen()
    {
        $this->popupPermit = true;
    }

    public function popupPermitClose()
    {
        $this->popupPermit = false;
    }
}
