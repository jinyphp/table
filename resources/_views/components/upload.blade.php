{{-- preload image--}}
@if(isset($form['image1']))
    @if (is_object($form['image1']))
        <!-- 업로드 미리보기 -->
        <img src="{{$form['image1']->temporaryUrl()}}" alt="">
    @else
        <!-- 저장된 이미지 보기 -->
        <img src="/images/{{$form['image1']}}" alt="">
    @endif
@endif

<div
    x-data="{ isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    >
    <!-- File Input -->
    <input type="file" name="filename" wire:model.defer="forms.image1" class="form-control"/>

    <!-- Progress Bar -->
    <div x-show="isUploading">
        <progress max="100" x-bind:value="progress"></progress>
    </div>
</div>

@error('filename') <span class="text-danger">{{$message}}</span> @enderror

