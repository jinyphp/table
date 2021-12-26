{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme theme="admin.sidebar2">
    <x-theme-layout>

        <ul>
            @foreach ($files as $item)
                <li>{{$item}}</li>
            @endforeach
        </ul>







    </x-theme-layout>
</x-theme>
