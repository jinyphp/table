<form id="main" action="{{route($actions['routename'].".store")}}" method="POST">
    @csrf
    @method('POST')
    <input type="hidden" name="__ajax" value="true">

    <x-card>
        <x-card-header>
            <h2>AjaxCreate</h2>
        </x-card-header>
        <x-card-body>
            {{-- dd($actions['nesteds']) --}}

            @includeIf($actions['view_form'])


            <x-card-footer>
                <x-button type="submit" name="_submit" primary>추가</x-button>

                @if (isset($actions['id']))
                    {{-- 삭제는 확인컨펌을 통하여 삭제처리 --}}
                    {{--
                    @if($confirm)
                        <x-button danger wire:click="delete">삭제</x-button>
                        <span>정말로 삭제할까요?</span>
                    @else
                        <x-button secondary wire:click="deleteConfirm">삭제</x-button>
                    @endif

                    <x-button primary wire:click="submit">수정</x-button>
                    --}}
                @else
                    {{--
                    <x-button secondary wire:click="clear">취소</x-button>
                    <x-button primary wire:click="submit">저장</x-button>
                    --}}
                @endif

            </x-card-footer>

        </x-card-body>
    </x-card>
</form>
