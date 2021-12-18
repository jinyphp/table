<div>

    <!-- Filter -->
    <x-card>
        <x-card-body>

        </x-card-body>
    </x-card>


    <x-card>
        <x-card-header>
            {{-- 페이징 --}}
            {!! xSelect()
                ->addOptions(['5'=>"5",'10'=>"10",'20'=>"20",'50'=>"50",'100'=>"100"])
                ->setWire('model',"paging")
                ->setWidth("tiny")
            !!}
        </x-card-header>
        <x-card-body>

            <x-datatable>
                <thead>
                    <tr>
                        <th width='20'>
                            <input type='checkbox' class="form-check-input" wire:model="selectedall">
                        </th>

                        <th width='100'>직급</th>
                        <th width='100'>사원수</th>
                        <th>메모</th>

                        <th width='200'>등록일자</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($rows))
                    @foreach ($rows as $item)

                    {{-- row-selected --}}
                    @if(in_array($item->id, $selected))
                    <tr class="row-selected">
                    @else
                    <tr>
                    @endif
                        <td width='20'>
                            <input type='checkbox' name='ids' value="{{$item->id}}"
                            class="form-check-input"
                            wire:model="selected">
                        </td>

                        <td width='100'>
                            {{--
                            @if($item->enable)
                                <a href="{{route($actions['routename'].".edit", $item->id)}}">{{$item->name}}</a>
                            @else
                                <a href="{{route($actions['routename'].".edit", $item->id)}}">
                                    <span style="text-decoration:line-through;">
                                    {{$item->name}}
                                    </span>
                                </a>
                            @endif
                            --}}
                            {!! $editLink($item, $item->name) !!}
                        </td>
                        <td width='100'></td>
                        <td></td>

                        <td width='200'>{{$item->created_at}}</td>
                    </tr>
                    @endforeach
                @else
                    사업자 목록이 없습니다.
                @endif
                </tbody>
            </x-datatable>


        </x-card-body>

        <x-card-footer>
            {{ $rows->links() }}

            {{-- 선택갯수 표시--}}
            <span id="selected-num">{{count($selected)}}</span>
            <span class="px-2">selected</span>

            @if (count($selected))
            <x-button danger small id="selected-delete" wire:click="popupDeleteOpen">
                선택삭제
            </x-button>
            @else
            <x-button danger small id="selected-delete" wire:click="popupDeleteOpen" disabled>
                선택삭제
            </x-button>
            @endif

        </x-card-footer>
    </x-card>

    {{-- 선택삭제 --}}
    @if ($popupDelete)
        <x-dialog-modal wire:model="popupDelete" maxWidth="2xl">

            <x-slot name="content">
                <p class="p-8">정말로 삭제할까요?</p>

            </x-slot>

            <x-slot name="footer">
                <x-button secondary wire:click="popupDeleteClose">취소</x-button>
                <x-button danger wire:click="checkeDelete">삭제</x-button>
            </x-slot>
        </x-dialog-modal>
    @endif


</div>





