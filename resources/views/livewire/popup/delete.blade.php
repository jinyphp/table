{{-- 선택삭제 --}}
@if ($popupDelete)
<x-dialog-modal wire:model="popupDelete" maxWidth="xl">

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
