<div>
    <x-card>
        <x-card-header>

        </x-card-header>
        <x-card-body>
            <x-navtab class="mb-3 nav-bordered">

                <!-- formTab -->
                <x-navtab-item class="show active" >

                    <x-navtab-link class="rounded-0 active">
                        <span class="d-none d-md-block">기본정보</span>
                    </x-navtab-link>

                    <x-form-hor>
                        <x-form-label>활성화</x-form-label>
                        <x-form-item>
                            {!! xCheckbox()
                                ->setWire('model.defer',"form.enable")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                    <x-form-hor>
                        <x-form-label>이름</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.name")
                                ->setWidth("standard")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                    <x-form-hor>
                        <x-form-label>국가</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.country")
                                ->setWidth("standard")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                    <x-form-hor>
                        <x-form-label>관리자</x-form-label>
                        <x-form-item>
                            {!! xSelect()
                                ->table('hr_employee','name')
                                ->setWire('model.defer',"form.manager")

                                ->setWidth("medium")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                </x-navtab-item>





                <!-- formTab -->
                <x-navtab-item >
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">메모</span>
                    </x-navtab-link>

                    <x-form-hor>
                        <x-form-label>메모</x-form-label>
                        <x-form-item>
                            {!! xTextarea()
                                ->setWire('model.defer',"form.description")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                </x-navtab-item>

            </x-navtab>

            {{--
            <x-card-footer>
                @if (isset($actions['id']))
                    <x-button primary wire:click="update">수정</x-button>
                    <x-button danger wire:click="delete">삭제</x-button>
                @else
                    <x-button primary wire:click="create">저장</x-button>
                    <x-button secondary wire:click="cancel">취소</x-button>
                @endif
            </x-card-footer>
            --}}
        </x-card-body>
    </x-card>
    <script>

    </script>
</div>
