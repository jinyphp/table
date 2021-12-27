<div>
    <!-- 팝업 데이터 수정창 -->
    @if ($popupForm)
    <x-dialog-modal wire:model="popupForm" maxWidth="2xl">
        <x-slot name="content">
            @includeIf($actions['view_form'])
        </x-slot>

        <x-slot name="footer">
            @if (isset($actions['id']))
                <div class="flex justify-between">
                    <div>
                        @if($confirm)
                            <x-button danger wire:click="delete">삭제</x-button>
                            <span>정말로 삭제할까요?</span>
                        @else
                            <x-button secondary wire:click="deleteConfirm">삭제</x-button>
                        @endif
                    </div>
                    <div>
                        <x-button secondary wire:click="popupFormClose">취소</x-button>
                        <x-button primary wire:click="update">수정</x-button>
                    </div>
                </div>
            @else
                <div class="flex justify-between">
                    <div></div>
                    <div class="text-right">
                        <x-button secondary wire:click="popupFormClose">취소</x-button>
                        <x-button primary wire:click="store">저장</x-button>
                    </div>
                </div>
            @endif
        </x-slot>
    </x-dialog-modal>
    @endif

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
</div>
