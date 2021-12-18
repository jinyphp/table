<x-theme theme="admin.sidebar2">
    <x-theme-layout>
        <!-- start page title -->
        @if (isset($actions['view_title']))
            @includeIf($actions['view_title'])
        @endif
        <!-- end page title -->

        @livewire('WireForm', ['actions'=>$actions])

    </x-theme-layout>
</x-theme>
