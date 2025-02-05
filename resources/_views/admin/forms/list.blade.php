<x-datatable>
    {{--
    <thead>
        <tr>
            <th width='20'>
                <input type='checkbox' class="form-check-input" wire:model="selectedall">
            </th>
            <th width='100'>국가</th>
            <th> {!! xWireLink('부서명', "orderBy('name')") !!}</th>
            <th width='100'>사원수</th>
            <th width='200'>관리자</th>
            <th width='200'>등록일자</th>
        </tr>
    </thead>
    --}}
    <x-data-table-thead>

        <th> {!! xWireLink('라벨', "orderBy('lavel')") !!}</th>
        <th width='200'>필드</th>
        <th width='200'>입력유형</th>
        <th width='200'>등록일자</th>
    </x-data-table-thead>

    @if(!empty($rows))
    <tbody>
        @foreach ($rows as $item)

        {{-- row-selected --}}
        {{--
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

            <td width='100'>{{$item->country}}</td>
            <td>
                {!! $popupEdit($item, $item->name) !!}
            </td>
            <td width='100'></td>
            <td width='200'>
                {{_getValue($item->manager)}}
            </td>

            <td width='200'>{{$item->created_at}}</td>
        </tr>
        --}}
        <x-data-table-tr :item="$item" :selected="$selected">

            <td>
                {!! $popupEdit($item, $item->label) !!}
            </td>

            <td width='200'>{{$item->field}}</td>
            <td width='200'>{{$item->input}}</td>
            <td width='200'>{{$item->created_at}}</td>
        </x-data-table-tr>
        @endforeach

    </tbody>
    @endif
</x-datatable>

@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif
