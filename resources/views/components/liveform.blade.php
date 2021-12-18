<div wire:loading>
    processing...
</div>
<div wire:loading.remove>
    <form wire:submit.prevent="submit" onkeydown="return event.key != 'Enter';">
        {{$slot}}
    </form>
</div>
