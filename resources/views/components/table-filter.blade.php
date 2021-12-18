<x-row>
    <x-col-6 class="mx-auto">
            {{$slot}}
    </x-col-6>
</x-row>

<x-row>
    <x-col-6 class="pt-3 mx-auto text-center border-t">
        <x-button primary wire:click="filter_search">검색</x-button>
        <x-button primary wire:click="filter_reset">취소</x-button>
    </x-col-6>
</x-row>



