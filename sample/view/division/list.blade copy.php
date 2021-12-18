<div>
    <!-- 검색 필터 -->
    <x-card>
        <x-card-body>
            <x-row>
                <x-col-6 class="mx-auto">
                    <x-form-hor>
                        <x-form-label>부서명</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"filter.name")
                                ->setWidth("small")
                            !!}
                        </x-form-item>
                    </x-form-hor>
                </x-col-6>
            </x-row>

            <x-row>
                <x-col-6 class="pt-3 mx-auto text-center border-t">
                    <x-button primary wire:click="filter_search">검색</x-button>
                    <x-button primary wire:click="filter_reset">취소</x-button>
                </x-col-6>
            </x-row>
        </x-card-body>
    </x-card>

    @if (session()->has('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <!-- 데이터 목록 -->
    <x-card>
        <x-card-header>

        </x-card-header>
        <x-card-body>

            <x-datatable>
                <thead>
                    <tr>
                        <th width='20'>
                            <input type='checkbox' class="form-check-input"
                            wire:model="selectedall">
                        </th>
                        <th width='100'>국가</th>
                        <th>부서명</th>
                        <th width='100'>사원수</th>
                        <th width='200'>관리자</th>

                        <th width='200'>등록일자</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($data))
                    @foreach ($data as $item)

                    {{-- row-selected --}}
                    @if(in_array($item['id'], $selected))
                    <tr class="row-selected">
                    @else
                    <tr>
                    @endif

                        <td width='20'>
                            <input type='checkbox' name='ids' value="{{$item['id']}}"
                            class="form-check-input"
                            wire:model="selected">
                        </td>

                        <td width='100'>{{$item['country']}}</td>
                        <td >
                            {{--
                            @if($item->enable)
                                <a href="#"
                                    wire:click="edit({{$item->id}})">{{$item->name}}</a>
                            @else
                                <a href="#"
                                    wire:click="edit({{$item->id}})">
                                    <span style="text-decoration:line-through;">
                                    {{$item->name}}
                                    </span>
                                </a>
                            @endif
                            --}}
                            @if($item['enable'])
                                <a href="javascript: void(0);"
                                    wire:click="$emit('edit','{{$item['id']}}')">{{$item['name']}}</a>
                            @else
                                <a href="javascript: void(0);"
                                    wire:click="$emit('edit','{{$item['id']}}')">
                                    <span style="text-decoration:line-through;">
                                    {{$item['name']}}
                                    </span>
                                </a>
                            @endif
                        </td>
                        <td width='100'></td>
                        <td width='200'>
                            {{_getValue($item['manager'])}}
                        </td>

                        <td width='200'>{{$item['created_at']}}</td>
                    </tr>
                    @endforeach
                @else
                    사업자 목록이 없습니다.
                @endif
                </tbody>
            </x-datatable>
        </x-card-body>

        <x-card-footer>
            {{-- $rows->links() --}}

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

                {{--
                @foreach ($selected as $item)
                    {{$item}}
                @endforeach
                --}}

            </x-slot>

            <x-slot name="footer">
                <x-button secondary wire:click="popupDeleteClose">취소</x-button>
                <x-button danger wire:click="checkeDelete">삭제</x-button>
            </x-slot>
        </x-dialog-modal>
    @endif

</div>
