{{--
    페이지 링크로 form 출력을 위한 양식 tag 입니다.
--}}
<div>
    <x-loading-indicator/>

    <x-card>
        <x-card-header>

        </x-card-header>
        <x-card-body>

            @includeIf($actions['view_form'])

            <x-card-footer>
                @if (isset($actions['id']))
                    {{-- 삭제는 확인컨펌을 통하여 삭제처리 --}}
                    @if($confirm)
                        <x-button danger wire:click="delete">삭제</x-button>
                    @else
                        <x-button secondary wire:click="deleteConfirm">삭제</x-button>
                    @endif

                    <x-button primary wire:click="submit">수정</x-button>

                @else
                    <x-button secondary wire:click="clear">취소</x-button>
                    <x-button primary wire:click="submit">저장</x-button>
                @endif

            </x-card-footer>

        </x-card-body>
    </x-card>
    <script>

    </script>
</div>
