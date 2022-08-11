<div>
    <x-loading-indicator/>

    {{-- 필터를 적용시 filter.blade.php 를 읽어 옵니다. --}}
    @if (isset($actions['view_filter']))
        @includeIf($actions['view_filter'])
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <div class="bg-white">
        {{-- header --}}
        <div class="p-2">
            {{-- 페이징 --}}
            {!! xSelect()
                ->addOptions(['5'=>"5",'10'=>"10",'20'=>"20",'50'=>"50",'100'=>"100"])
                ->setWire('model',"paging")
                ->setWidth("tiny")
            !!}
        </div>
        {{-- body --}}
        <div class="p-2 overflow-x-auto">
            @if (isset($actions['view_list']))
                @includeIf($actions['view_list'])
            @endif
        </div>
        {{-- footer --}}
        <div class="p-2">

            @if (isset($rows) && is_object($rows))
                @if(method_exists($rows, "links"))
                {{ $rows->links() }}
                @endif
            @endif





            {{-- 선택갯수 표시--}}
            <span id="selected-num">{{count($selected)}}</span>
            <span class="px-2">selected</span>

            @if (count($selected))
            <x-button danger small id="selected-delete" wire:click="popupDeleteOpen">
                선택삭제
            </x-button>
            @else
            <x-button danger small id="selected-delete" wire:click="popupDeleteOpen" disabled>
                선택삭제
            </x-button>
            @endif
        </div>
    </div>


    {{-- 선택삭제 --}}
    @include("jinytable::livewire.popup.delete")

    {{-- 퍼미션 알람--}}
    @include("jinytable::error.popup.permit")

</div>
