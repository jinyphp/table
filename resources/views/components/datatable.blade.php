<style>
    .row-selected {
        background-color: #fcf7c2;
    }
</style>

<table {{$attributes->merge(['class' => 'table datatable'])}}>
    {{$slot}}
</table>



