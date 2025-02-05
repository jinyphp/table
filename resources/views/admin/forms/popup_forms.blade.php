<div>
    <!-- 팝업 데이터 수정창 -->
    @if ($popupForm)
        <x-wire-dialog-modal wire:model="popupForm" :maxWidth="$popupWindowWidth">
            <x-slot name="title">
                @if (isset($actions['id']))
                    {{ __('수정') }}
                @else
                    {{ __('입력') }}
                @endif
            </x-slot>

            <x-slot name="content">

                @if ($viewForm)
                    @includeIf($viewForm)
                @else
                    입력폼 양식이 지정되지 않았습니다. <br>
                    컨트롤러 actions의 view->form 항목을 설정해 주세요.
                @endif
            </x-slot>

            <x-slot name="footer">
                @if ($message)
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                @endif

                @if (isset($actions['view']['form']))
                    @if (isset($actions['id']))
                        {{-- 수정폼 --}}
                        <div class="flex justify-between">
                            <div> {{-- 2단계 삭제 --}}
                                @if ($popupDelete)
                                    <span class="text-red-600">정말로 삭제를 진행할까요?</span>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        wire:click="deleteConfirm">삭제</button>
                                @else
                                    <button type="button" class="btn btn-danger" wire:click="delete">삭제</button>
                                @endif
                            </div>
                            <div> {{-- right --}}
                                <button type="button" class="btn btn-secondary" wire:click="cancel">취소</button>
                                <button type="button" class="btn btn-info" wire:click="update">수정</button>
                            </div>
                        </div>
                    @else
                        {{-- 생성폼 --}}
                        <div class="flex justify-between">
                            <div></div>
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" wire:click="cancel">취소</button>
                                <button type="button" class="btn btn-primary" wire:click="store">저장</button>
                            </div>
                        </div>
                    @endif
                @else
                    <button type="button" class="btn btn-secondary" wire:click="cancel">취소</button>
                @endif
            </x-slot>
        </x-wire-dialog-modal>
    @endif

    {{-- 퍼미션 알람 --}}
    {{-- @if ($popupPermit)
        @include('jiny-wire-table::table_popup_forms.permit')
    @endif --}}
</div>
