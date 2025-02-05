<x-theme>
    {{-- 설정 파일을 생성할 수 있는 출력 템플릿 --}}
    <x-theme-layout>
        <!-- Module Title Bar -->
        @if(Module::has('Titlebar'))
            @livewire('TitleBar', ['actions'=>$actions])
        @endif
        <!-- end -->
        {{--
        <!-- start page title -->
        @if (isset($actions['view_title']) && !empty($actions['view_title']))
            @includeIf($actions['view_title'])
        @else
            @include("jinytable::title")
        @endif
        <!-- end page title -->
        --}}

        @livewire('WireConfig', ['actions'=>$actions])

        {{-- Admin Rule Setting --}}
        @include('jinytable::setActionRule')

    </x-theme-layout>
</x-theme>

