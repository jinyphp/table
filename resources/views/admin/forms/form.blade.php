<div>
    <x-navtab class="mb-3 nav-bordered">

        @php
            $uri = "/".$this->actions['route']['uri'];
            $_forms = DB::table('table_forms')
                ->where('uri',$uri)
                ->where('enable',1)
                ->orderby('pos',"asc")->get();

            function xFormHorizontal($label, $input) {
                $rowDiv = new \Jiny\Html\CTag('div',true);

                $xLabel = new \Jiny\Html\CTag('label',true);
                $xLabel->addItem($label);
                $xLabel->addClass("col-sm-2 col-form-label");

                $xCol = new \Jiny\Html\CTag('div',true);
                $xCol->addClass("col-sm-10");
                $xCol->addItem($input);

                $rowDiv->addItem($xLabel);
                $rowDiv->addItem($xCol);

                return $rowDiv->addClass("row mb-3");
            }

            $formTabs = [];
            @endphp

            @foreach ($_forms as $item)
                @php
                    if($item->input == "text") {
                        $inputType = "xInputText";
                    } else if($item->input == "checkbox") {
                        $inputType = "xCheckbox";
                    }

                    if($inputType) {
                        $obj = $inputType()
                            ->setWire('model.defer',"forms.".$item->field);
                    }

                    $tab = $item->tab;
                    if($tab) {

                    } else {
                        $tab = "basic";
                    }

                    $formTabs[$tab] []= xFormHorizontal($item->label,$obj);

                @endphp
            @endforeach


            @foreach ($formTabs as $tabname => $tabs)
                @if ($loop->first)

                @endif


                <x-navtab-item class="" >
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">{{$tabname}}</span>
                    </x-navtab-link>


                    @foreach ($tabs as $tab)
                        {!! $tab !!}
                    @endforeach
                </x-navtab-item>
            @endforeach



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
