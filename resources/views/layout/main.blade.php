{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme theme="admin.sidebar">
    <x-theme-layout>

        <!-- Module Title Bar -->
        @if(Module::has('Titlebar'))
            @livewire('TitleBar', ['actions'=>$actions])
        @endif

        <div class="relative">
            <div class="absolute right-0 bottom-4">
                <div class="btn-group">
                    <x-button id="btn-livepopup-manual" secondary wire:click="$emit('popupManualOpen')">메뉴얼</x-button>
                    <x-button id="btn-livepopup-create" primary wire:click="$emit('popupFormOpen')">신규추가</x-button>
                </div>
            </div>
        </div>


        @push('scripts')
        <script>
            document.querySelector("#btn-livepopup-create").addEventListener("click",function(e){
                e.preventDefault();
                Livewire.emit('popupFormCreate');
            });

            document.querySelector("#btn-livepopup-manual").addEventListener("click",function(e){
                e.preventDefault();
                Livewire.emit('popupManualOpen');
            });
        </script>
        @endpush

        <!-- 테이블 목록 출력  -->
        @livewire('WireTable', ['actions'=>$actions])



        {{-- @livewire('Popup-LiveForm', ['actions'=>$actions]) --}}
        @livewire('LivewireFormPopup', ['actions'=>$actions])
        

        @livewire('Popup-LiveManual')

        {{-- Admin Rule Setting --}}
        @include('jinytable::setActionRule')

        {{-- popup UI Design mode --}}
        @livewire('DesignForm')

    </x-theme-layout>
</x-theme>
