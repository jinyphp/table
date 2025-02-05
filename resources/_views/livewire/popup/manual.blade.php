<div>
    <!-- 팝업 데이터 수정창 -->
    @if ($popupManual)
    <x-dialog-modal wire:model="popupManual" maxWidth="2xl">

        <x-slot name="content">
            설명내용
        </x-slot>

        <x-slot name="footer">
            <x-button secondary wire:click="popupManualClose">닫기</x-button>
        </x-slot>
    </x-dialog-modal>
    @endif
</div>
