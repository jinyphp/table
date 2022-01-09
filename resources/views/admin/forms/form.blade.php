<div>


    {{--
    <style>
        .nav-tabs.nav-bordered li .tab-header {
            border: 0;
            padding: .625rem 1.25rem;
        }

        .nav-tabs.nav-bordered li .tab-header.active {
            background-color: #fff;
            border-bottom: 2px solid #727cf5;
        }
    </style>
    --}}


    {!! xFormBuilder($actions, "nav-bordered") !!}


    {{--  --}}
    @if ($popupTabbar)
    <x-dialog-modal wire:model="popupTabbar" maxWidth="xl">

        <x-slot name="content">
            <p class="p-8">{{$popupTabbarMessage}}</p>


            <x-form-hor>
                <x-form-label>탭이름</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"tabname")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>
        </x-slot>

        <x-slot name="footer">
            @if (isset($tabid))
                <x-button danger wire:click="popupTabbarDelete">삭제</x-button>
                <x-button secondary wire:click="popupTabbarClose">취소</x-button>
                <x-button success wire:click="popupTabbarSave">수정</x-button>
            @else
                <x-button secondary wire:click="popupTabbarClose">취소</x-button>
                <x-button primary wire:click="popupTabbarSave">저장</x-button>
            @endif

        </x-slot>
    </x-dialog-modal>
    @endif

</div>
