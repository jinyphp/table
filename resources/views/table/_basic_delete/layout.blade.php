<div>
    {{-- <x-loading-indicator/> --}}

    {{-- 필터 --}}
    <div class="mb-3">
        @includeIf('jiny-wire-table::table_popup_forms.filter')

        {{-- 메시지를 출력합니다. --}}
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
    </div>

    <div class="mb-3">
        {{-- 외부에서 지정한 목록 테이블을 출력합니다. --}}
        @if (isset($actions['view']['list']))
            @includeIf($actions['view']['list'])
        @endif

        @if (empty($rows))
            <div class="text-center">
                검색된 데이터 목록이 없습니다.
            </div>
        @endif
    </div>

    <div class="mb-3">
        {{-- 페이지네이션 --}}
        <div class="d-flex justify-content-between">
            <div class="">
                전체 {{ count($ids) }} 개가 검색되었습니다.
            </div>
            <div>
                @if (isset($rows) && is_object($rows))
                    @if (method_exists($rows, 'links'))
                        <div>{{ $rows->links() }}</div>
                    @else
                    @endif
                @else
                @endif
            </div>
            <div>
                <button class="btn btn-primary btn-sm" wire:click="create">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                    <span>추가</span>
                </button>
            </div>
        </div>

    </div>

    <div class="mb-3">
        {{-- 선택삭제 기능 --}}
        <div class="d-flex justify-content-between">
            <div>
                @if (isset($actions['delete']['check']) && $actions['delete']['check'])
                    @includeIf('jiny-site-partner::table.check_delete')
                @endif
            </div>
            <div>

            </div>
        </div>
    </div>

</div>
