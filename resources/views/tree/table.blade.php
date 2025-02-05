<section>
    <div class="card my-2">
        <div class="pt-2">

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
    </div>

    <button class="btn btn-primary btn-sm" wire:click="create">
        추가
    </button>
</section>
