{{-- 타이틀 제목을 출력하기 위한 템플릿 --}}
<x-row>
    <x-col class="col-8">
        <div class="page-title-box">
            <ol class="m-0 breadcrumb">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                @if (isset($actions['route']['uri']))
                    @foreach (explode("/",$actions['route']['uri']) as $item)
                        @if($item != "{id}")
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{$item}}</a></li>
                        @endif
                    @endforeach
                @endif
            </ol>

            <div class="mb-3">
                <h1 class="align-middle h3 d-inline">
                    @if (isset($actions['title']))
                        {{$actions['title']}}
                    @else
                        ...
                    @endif
                </h1>
                <p>
                    @if (isset($actions['subtitle']))
                        {{$actions['subtitle']}}
                    @else
                        ...
                    @endif
                </p>
            </div>
        </div>
    </x-col>
</x-row>
