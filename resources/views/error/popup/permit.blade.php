<!-- 팝업 데이터 수정창 -->
@if ($popupPermit)
<x-dialog-modal wire:model="popupPermit" maxWidth="2xl">
    <x-slot name="content">
        <br/>
        사용자 권한이 없습니다.
        <br/>
    </x-slot>

    <x-slot name="footer">
        <div class="flex justify-between">
            <div></div>
            <div class="text-right">
                <x-button secondary wire:click="popupPermitClose">닫기</x-button>
            </div>
        </div>
    </x-slot>
</x-dialog-modal>
@endif
