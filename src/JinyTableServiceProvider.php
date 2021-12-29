<?php

namespace Jiny\Table;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;

use Livewire\Livewire;

class JinyTableServiceProvider extends ServiceProvider
{
    private $package = "jinytable";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // 팝업 Dialog
        Blade::component($this->package.'::components.'.'dialog-modal', 'dialog-modal');
        Blade::component($this->package.'::components.'.'modal', 'modal');

        Blade::component($this->package.'::components.'.'loading-indicator', 'loading-indicator');

        // 테이블 컴포넌트...
        Blade::component($this->package.'::components.'.'datatable', 'datatable');
        Blade::component($this->package.'::components.'.'datatable', 'data-table');
        Blade::component(\Jiny\Table\View\Components\DataTableThead::class, "datatable-thead");
        Blade::component(\Jiny\Table\View\Components\DataTableThead::class, "data-table-thead");
        Blade::component(\Jiny\Table\View\Components\DataTableTr::class, "datatable-tr");
        Blade::component(\Jiny\Table\View\Components\DataTableTr::class, "data-table-tr");


        Blade::component($this->package.'::components.'.'table-filter', 'table-filter');


        Blade::component($this->package.'::components.'.'data-delete', 'datatable.check-delete');

        Blade::component($this->package.'::components.'.'liveform', 'live-form');
        Blade::component($this->package.'::components.'.'livesubmit', 'live-submit');

    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            // AlpineJS 를 이용항 Table
            Livewire::component('WireTable', \Jiny\Table\Http\Livewire\WireTable::class);
            Livewire::component('WireForm', \Jiny\Table\Http\Livewire\WireForm::class); // 페이지 이동
            Livewire::component('Popup-LiveForm', \Jiny\Table\Http\Livewire\PopupForm::class); // 팝업형
            Livewire::component('Popup-LiveManual', \Jiny\Table\Http\Livewire\PopupManual::class);


            Livewire::component('WireFiles', \Jiny\Table\Http\Livewire\WireFiles::class);
            Livewire::component('WireFileEdit', \Jiny\Table\Http\Livewire\WireFileEdit::class);


            // Form => json 저장
            Livewire::component('WireConfig', \Jiny\Table\Http\Livewire\WireConfig::class);

            Livewire::component('setActionRule', \Jiny\Table\Http\Livewire\SetActionRule::class);

            // 데쉬보드
            Livewire::component('WireDashBoard', \Jiny\Table\Http\Livewire\WireDashBoard::class);



        });
    }


}
