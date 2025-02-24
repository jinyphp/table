<?php
namespace Jiny\Table;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

class JinyTableServiceProvider extends ServiceProvider
{
    private $package = "jiny-table";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // // 팝업 Dialog
        // Blade::component($this->package.'::components.'.'dialog-modal', 'dialog-modal');
        // Blade::component($this->package.'::components.'.'modal', 'modal');

        // Blade::component($this->package.'::components.'.'loading-indicator', 'loading-indicator');

        // // 테이블 컴포넌트...
        // Blade::component($this->package.'::components.'.'datatable', 'datatable');
        // Blade::component($this->package.'::components.'.'datatable', 'data-table');
        // Blade::component(\Jiny\Table\View\Components\DataTableThead::class, "datatable-thead");
        // Blade::component(\Jiny\Table\View\Components\DataTableThead::class, "data-table-thead");
        // Blade::component(\Jiny\Table\View\Components\DataTableTr::class, "datatable-tr");
        // Blade::component(\Jiny\Table\View\Components\DataTableTr::class, "data-table-tr");


        // Blade::component($this->package.'::components.'.'table-filter', 'table-filter');


        // Blade::component($this->package.'::components.'.'data-delete', 'datatable.check-delete');

        // Blade::component($this->package.'::components.'.'liveform', 'live-form');
        // Blade::component($this->package.'::components.'.'livesubmit', 'live-submit');

        // // form 버튼
        // Blade::component($this->package.'::components.'.'PopupFormCreate', 'popup-form-create');


        // // javascript emit 버튼
        // Blade::component($this->package.'::components.'.'wire.create', 'btn-emitCreate');
        // Blade::component($this->package.'::components.'.'wire.manual', 'btn-emitManual');

    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            // 기본 테이블: 전체 목록을 출력합니다.
            Livewire::component(
                'table',
                \Jiny\Table\Http\Livewire\Table::class);

            // 검색과 페이징을 통하여 테이블을 출력합니다.
            Livewire::component(
                'table-filter',
                \Jiny\Table\Http\Livewire\TableFilter::class);

            // 테이블 + 선택삭제
            Livewire::component(
                'table-delete',
                \Jiny\Table\Http\Livewire\TableCheckDelete::class);

            // 삭제 및 생성 버튼
            Livewire::component(
                'table-delete-create',
                \Jiny\Table\Http\Livewire\TableDeleteCreate::class);

            // 폼 팝업
            Livewire::component(
                'form-popup',
                \Jiny\Table\Http\Livewire\FormPopup::class);



            // 테이블
            Livewire::component(
                'site-table',
                \Jiny\Table\Http\Livewire\SiteTable::class);

            Livewire::component(
                'admin-table-none',
                \Jiny\Table\Http\Livewire\AdminTableNone::class);

            Livewire::component(
                'admin-table',
                \Jiny\Table\Http\Livewire\AdminTable::class);

            Livewire::component(
                'site-table-none',
                \Jiny\Table\Http\Livewire\SiteTableNone::class);


            // Form팝업
            Livewire::component(
                'site-form-popup',
                \Jiny\Table\Http\Livewire\SiteFormPopup::class);

            Livewire::component(
                'admin-form-popup',
                \Jiny\Table\Http\Livewire\AdminFormPopup::class);



            // 트리구조 테이블블
            Livewire::component(
                'tree-delete-create',
                \Jiny\Table\Http\Livewire\TreeDeleteCreate::class);

            Livewire::component(
                'tree-form-popup',
                \Jiny\Table\Http\Livewire\TreeFormPopup::class);



            // AlpineJS 를 이용항 Table
            //# 모듈이동 Livewire::component('TableTitle', \Jiny\Table\Http\Livewire\TableTitle::class);


            // Livewire::component('WireTable', \Jiny\Table\Http\Livewire\WireTable::class);
            // Livewire::component('WireCheckDelete', \Jiny\Table\Http\Livewire\WireCheckDelete::class);
            // Livewire::component('AdminTable', \Jiny\Table\Http\Livewire\AdminTable::class);
            // Livewire::component('LivewireTable', \Jiny\Table\Http\Livewire\LivewireTable::class);


            // Livewire::component('LivewireFormPopup', \Jiny\Table\Http\Livewire\LivewireFormPopup::class); // 팝업형
            // Livewire::component('Popup-LiveForm', \Jiny\Table\Http\Livewire\PopupForm::class); // 팝업형
            // Livewire::component('Popup-LiveManual', \Jiny\Table\Http\Livewire\PopupManual::class);


            // Livewire::component('WireForm', \Jiny\Table\Http\Livewire\WireForm::class); // 페이지 이동

            // Livewire::component('WireDetail', \Jiny\Table\Http\Livewire\WireDetail::class);





            // Livewire::component('JsonTable', \Jiny\Table\Http\Livewire\JsonTable::class);


            // Livewire::component('WireFiles', \Jiny\Table\Http\Livewire\WireFiles::class);
            // Livewire::component('WireFileEdit', \Jiny\Table\Http\Livewire\WireFileEdit::class);


            // // Form => json 저장
            // Livewire::component('WireConfig', \Jiny\Table\Http\Livewire\WireConfig::class);




            // Livewire::component('Test', \Jiny\Table\Http\Livewire\Test::class);

            // // ui Design
            // Livewire::component('DesignForm', \Jiny\Table\Http\Livewire\DesignForm::class);


        });
    }


}
