<div>
    <style>

    </style>
    <!-- 팝업 Rule 수정창 -->
    @if ($popupRule)
    <x-dialog-modal wire:model="popupRule" maxWidth="2xl">
        <x-slot name="content">
            <x-navtab class="mb-3 nav-bordered">
                <x-navtab-item><!-- Action 정보 -->
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">정보</span>
                    </x-navtab-link>

                    <fieldset>
                        <legend class="px-2 text-xs">Blade Resource</legend>

                        <x-form-hor>
                            <x-form-label>View_title </x-form-label>
                            <x-form-item>
                                {!! xInputText()
                                    ->setWire('model.defer',"form.view_title")
                                    ->setWidth("standard")
                                !!}
                            </x-form-item>
                        </x-form-hor>

                    </fieldset>

                    <fieldset>
                        <legend class="px-2 text-xs">Argument</legend>
                        <x-form-hor>
                            <x-form-label>타이틀</x-form-label>
                            <x-form-item>
                                {!! xInputText()
                                    ->setWire('model.defer',"form.title")
                                !!}
                            </x-form-item>
                        </x-form-hor>

                        <x-form-hor>
                            <x-form-label>서브타이틀</x-form-label>
                            <x-form-item>
                                {!! xTextarea()
                                    ->setWire('model.defer',"form.subtitle")
                                !!}
                            </x-form-item>
                        </x-form-hor>
                    </fieldset>
                </x-navtab-item>


                <x-navtab-item class="show active"><!-- formTab -->
                    <x-navtab-link class="rounded-0 active">
                        <span class="d-none d-md-block">목록</span>
                    </x-navtab-link>

                    <fieldset>
                        <legend class="px-2 text-xs">Blade Resource</legend>

                        <x-form-hor>
                            <x-form-label>view_main</x-form-label>
                            <x-form-item>
                                {!! xInputText()
                                    ->setWire('model.defer',"form.view_main")
                                    ->setWidth("standard")
                                !!}
        {{--
                                <a href="javascript: void(0);" wire:click="resourceEdit('view_main')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                --}}
                            </x-form-item>
                        </x-form-hor>

                        <x-form-hor>
                            <x-form-label>view_filter</x-form-label>
                            <x-form-item>
                                {!! xInputText()
                                    ->setWire('model.defer',"form.view_filter")
                                    ->setWidth("standard")
                                !!}
                            </x-form-item>
                        </x-form-hor>

                        <x-form-hor>
                            <x-form-label>view_list</x-form-label>
                            <x-form-item>
                                {!! xInputText()
                                    ->setWire('model.defer',"form.view_list")
                                    ->setWidth("standard")
                                !!}
                            </x-form-item>
                        </x-form-hor>

                    </fieldset>
                </x-navtab-item>

                <x-navtab-item ><!-- formTab -->
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">입력폼</span>
                    </x-navtab-link>

                    <x-form-hor>
                        <x-form-label>view_edit</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.view_edit")
                                ->setWidth("standard")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                    <x-form-hor>
                        <x-form-label>view_form</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.view_form")
                                ->setWidth("standard")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                </x-navtab-item>

                <x-navtab-item ><!-- formTab -->
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">데이터베이스</span>
                    </x-navtab-link>

                    <x-form-hor>
                        <x-form-label>테이블</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.table")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                    <x-form-hor>
                        <x-form-label>페이징</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.paging")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                    <x-form-hor>
                        <x-form-label>조건</x-form-label>
                        <x-form-item>
                            {!! xInputText()
                                ->setWire('model.defer',"form.where")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                </x-navtab-item>

                <x-navtab-item >
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">메뉴</span>
                    </x-navtab-link>

                    <x-form-hor>
                        <x-form-label>메뉴</x-form-label>
                        <x-form-item>
                            {{-- xInputText()
                                ->setWire('model.defer',"form.menu")
                            --}}

                            {!! xSelect()
                                ->table('menus','code')
                                ->setWire('model.defer',"form.menu")
                                ->setWidth("medium")
                            !!}
                        </x-form-item>
                    </x-form-hor>

                </x-navtab-item>


                <x-navtab-item ><!-- formTab -->
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

        </x-slot>

        <x-slot name="footer">
            @if (isset($actions['id']))
                <div class="flex justify-between">
                    <div>

                    </div>
                    <div>
                        <x-button secondary wire:click="popupRuleClose">취소</x-button>
                        <x-button primary wire:click="update">수정</x-button>
                    </div>
                </div>
            @else
                <div class="flex justify-between">
                    <div></div>
                    <div class="text-right">
                        <x-button secondary wire:click="popupRuleClose">취소</x-button>
                        <x-button primary wire:click="save">저장</x-button>
                    </div>
                </div>
            @endif
        </x-slot>
    </x-dialog-modal>
    @endif

    @if ($popupResourceEdit)
    <x-dialog-modal wire:model="popupResourceEdit" maxWidth="2xl">
        <x-slot name="content">
            {!! xTextarea()
                ->setWire('model.defer',"content")
            !!}
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between">
                <div></div>
                <div class="text-right">
                    <x-button secondary wire:click="returnRule">취소</x-button>
                    <x-button primary wire:click="update">수정</x-button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
    @endif

</div>
