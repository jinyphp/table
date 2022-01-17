<x-button id="btn-livepopup-create" primary wire:click="$emit('popupFormCreate','0')">{{$slot}}</x-button>
@once
    @push('scripts')
        <script>
            document.querySelector("#btn-livepopup-create")
                .addEventListener("click",function(e){
                    e.preventDefault();
                    Livewire.emit('popupFormCreate','0');
                });
        </script>
    @endpush
@endonce
