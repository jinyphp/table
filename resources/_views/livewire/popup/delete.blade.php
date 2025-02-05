{{-- 선택삭제 --}}
@if ($popupDelete)
    <x-boot-dialog wire:model="popupDelete" maxWidth="2xl" opacity="opacity-30">
        <x-slot name="title">
            {{__('레코드 삭제')}}
        </x-slot>

        <x-slot name="content">
            <div class="flex w-full p-3 space-x-5 lg:p-6 grow">
                <div class="flex items-center justify-center flex-none w-16 h-16 bg-red-100 rounded-full">
                    <svg class="inline-block w-8 h-8 text-red-500 hi-solid hi-shield-exclamation" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h4 class="mb-1 text-lg">
                        정말로 삭제를 진행할까요? 삭제후에는 되살릴 수 없습니다!!!
                        <br>
                        삭제를 원하시면 코드를 입력해 주세요.
                    </h4>
                    {{--
                    <div class="d-flex align-items-center">
                        <div class="text-gray-600 mt-2 py-2">
                            삭제코드 : {{$delete_code}}
                        </div>

                        <span class="ml-2 py-2" wire:click="delete_code_reload">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="grow">
                            <input type="text" class="form-control" wire:model="delete_confirm_code">
                        </div>
                        <div class="ml-2">
                            <span wire:click="delete_code_apply">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    --}}
                    

                    {{-- 메시지출력 --}}
                    @if (session()->has('message'))
                    <div class="text-red-800 mt-2">
                            {{session('message')}}
                    </div>
                    @endif

                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-secondary" wire:click="deleteCancel">
                취소
            </button>
            <button class="btn btn-danger" wire:click="checkeDelete">
                예, 삭제를 진행합니다.
            </button>
        </x-slot>
    </x-boot-dialog>


@endif
