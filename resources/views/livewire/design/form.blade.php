<div>
    {{-- 선택삭제 --}}
    @if ($popupDesgin)
    <x-dialog-modal wire:model="popupDesgin" maxWidth="xl">

        <x-slot name="content">
            <p class="p-8">Popup Design</p>

            {{--
            @foreach ($selected as $item)
                {{$item}}
            @endforeach
            --}}

        </x-slot>

        <x-slot name="footer">
            <x-button secondary wire:click="popupDesignClose">취소</x-button>

        </x-slot>
    </x-dialog-modal>
    @endif
</div>
