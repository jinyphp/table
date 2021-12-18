@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>

    @if (isset($content))
    <div class="px-2 py-1">
        {{ $content }}
    </div>
    @endif

    @if (isset($footer))
    <div class="px-4 py-3 bg-gray-100">
        {{ $footer }}
    </div>
    @endif

</x-modal>
