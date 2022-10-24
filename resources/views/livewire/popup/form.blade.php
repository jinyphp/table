<div>
    <x-loading-indicator/>

    <!-- 팝업 데이터 수정창 -->
    @if ($popupForm)
    <x-boot-dialog wire:model="popupForm" maxWidth="4xl">
        <x-slot name="title">
            {{__('레코드 입력 및 수정')}}
        </x-slot>

        <x-slot name="content">
            @includeIf($actions['view_form'])
        </x-slot>

        <x-slot name="footer">
            @if (isset($actions['id']))
                <div class="flex justify-between">
                    <div>
                        <button class="btn btn-danger" wire:click="delete">삭제</button>
                    </div>
                    <div>
                        <button class="btn btn-secondary" wire:click="popupFormClose">취소</button>
                        <button class="btn btn-primary" wire:click="update">수정</button>
                    </div>
                </div>
            @else
                <div class="flex justify-between">
                    <div></div>
                    <div class="text-right">
                        <button class="btn btn-secondary" wire:click="popupFormClose">취소</button>
                        <button class="btn btn-primary" wire:click="store">저장</button>
                    </div>
                </div>
            @endif
        </x-slot>
    </x-boot-dialog>
    @endif



    {{-- LivewireFormPopup --}}
    @include("jinytable::livewire.popup.WirePopupDelete")



    @if (isset($error) && $error)
    <x-popup-dialog wire:model="error" maxWidth="2xl" opacity="opacity-30">
        <x-slot name="title">
            {{__('오류')}}
        </x-slot>

        <x-slot name="content">
            {{$message}}
        </x-slot>

        <x-slot name="footer">
            <x-btn-second wire:click="closeError">
                닫기
            </x-btn-second>
        </x-slot>
    </x-popup-dialog>
    @endif


    {{-- 퍼미션 알람--}}
    @include("jinytable::error.popup.permit")

</div>
