<div>
    {{-- 선택삭제 --}}
    @if ($popupDesgin)
    <x-dialog-modal wire:model="popupDesgin" maxWidth="xl">

        <x-slot name="content">
            <p class="p-8">Popup Design</p>
            @includeIf($actions['view_form'])
        </x-slot>

        <x-slot name="footer">
            <x-button secondary wire:click="popupDesignClose">취소</x-button>
            <x-button primary wire:click="popupDesignStore">적용</x-button>
        </x-slot>
    </x-dialog-modal>
    @endif
</div>
