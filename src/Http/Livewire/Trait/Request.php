<?php
/**
 * Hook를 검색 처리합니다.
 */
namespace Jiny\Table\Http\Livewire\Trait;

trait Request
{
    public function request()
    {
        return $this->actions['request'];
    }
}
