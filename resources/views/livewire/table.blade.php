{{--
    테이블 목록을 출력합니다.
--}}
<div>

    <x-loading-indicator/>


    {{-- 필터를 적용시 filter.blade.php 를 읽어 옵니다. --}}
    @if (isset($actions['view_filter']))
        @includeIf($actions['view_filter'])
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <!-- 데이터 목록 -->
    <x-card>
        <x-card-header>
            {{-- 페이징 --}}
            {!! xSelect()
                ->addOptions(['5'=>"5",'10'=>"10",'20'=>"20",'50'=>"50",'100'=>"100"])
                ->setWire('model',"paging")
                ->setWidth("tiny")
            !!}
        </x-card-header>
        <x-card-body>
            @if (isset($actions['view_list']))
                @includeIf($actions['view_list'])
            @endif

        </x-card-body>

        <x-card-footer>
            @if (isset($row) && is_object($row))
                {{ $rows->links() }}
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

        </x-card-footer>
    </x-card>


    {{-- 선택삭제 --}}
    @if ($popupDelete)
        <x-dialog-modal wire:model="popupDelete" maxWidth="2xl">

            <x-slot name="content">
                <p class="p-8">정말로 삭제할까요?</p>

                {{--
                @foreach ($selected as $item)
                    {{$item}}
                @endforeach
                --}}

            </x-slot>

            <x-slot name="footer">
                <x-button secondary wire:click="popupDeleteClose">취소</x-button>
                <x-button danger wire:click="checkeDelete">삭제</x-button>
            </x-slot>
        </x-dialog-modal>
    @endif

    {{-- 퍼미션 알람--}}
    @include("jinytable::error.popup.permit")

</div>
