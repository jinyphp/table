<div>
    <x-navtab class="mb-3 nav-bordered">
        <x-navtab-item class="show active" >
            <x-navtab-link class="rounded-0 active">
                <span class="d-none d-md-block">Field</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>활성화</x-form-label>
                <x-form-item>
                    {!! xCheckbox()
                        ->setWire('model.defer',"forms.enable")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>uri</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.uri")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            {{-- 기준 데이터베이스 테이블 --}}
            <x-form-hor>
                <x-form-label>DB Tablename</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.tablename")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            {{-- 출력할 필드명을 선택합니다. --}}
            <x-form-hor>
                <x-form-label>필드명</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.field")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            {{-- 필드를 추가할 경우, 참조되는 값입니다. --}}
            <x-form-hor>
                <x-form-label>필드 타입</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.type")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>


        <!-- formTab -->
        <x-navtab-item class="" >

            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">컬럼출력</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>컬럼 숨김</x-form-label>
                <x-form-item>
                    {!! xCheckbox()
                        ->setWire('model.defer',"forms.display")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>타이틀</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.title")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>



            <x-form-hor>
                <x-form-label>출력순서</x-form-label>
                <x-form-item>
                    {!! xSelect()
                        ->rangeOption(20)
                        ->setWire('model.defer',"forms.pos")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>가로폭</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.width")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>정렬</x-form-label>
                <x-form-item>
                    <input type="text" wire:model="forms.sort">

                    {!! xInputText()
                        ->setWire('model.defer',"forms.sort")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

        <!-- formTab -->
        <x-navtab-item >
            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">셀타입</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>셀유형</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.cell")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>수정링크</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.edit")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>링크</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.link")
                        ->setWidth("standard")
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
                        ->setWire('model.defer',"forms.description")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

    </x-navtab>
</div>
