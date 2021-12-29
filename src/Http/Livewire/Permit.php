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
        if( $user = Auth::user() ) {
            if (function_exists("authRoles")) {
                $Role = authRoles($user->id);
                //$Role = new \Jiny\Auth\Roles($user->id);
                $this->permit = $Role->permitAll($this->actions);

            } else {
                // jiny/auth 모듈이 설치되어 있지 않는 경우,
                // 모두 허용
                $this->permit = [
                    'create' => true,
                    'read' => true,
                    'update' => true,
                    'delete' => true,
                ];
            }
        } else {
            // 회원인증 없음.
            if(isset($this->actions['role']) && $this->actions['role']) {
                $this->permit = [
                    'create' => false,
                    'read' => false,
                    'update' => false,
                    'delete' => false,
                ];

                foreach($this->actions['roles'] as $role)
                {
                    if(isset($role['permit']) && $role['permit']) {
                        if(isset($role['create']) && $role['create']) $this->permit['create'] = true;
                        if(isset($role['read']) && $role['read']) $this->permit['read'] = true;
                        if(isset($role['update']) && $role['update']) $this->permit['update'] = true;
                        if(isset($role['delete']) && $role['delete']) $this->permit['delete'] = true;
                    }
                }
            } else {
                // 권한설정 미적용시,
                // 모두 허용
                $this->permit = [
                    'create' => true,
                    'read' => true,
                    'update' => true,
                    'delete' => true,
                ];
            }
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
