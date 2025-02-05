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

    {{$slot}}
</tr>
