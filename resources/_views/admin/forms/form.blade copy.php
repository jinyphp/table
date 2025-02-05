<div>
    {{--
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
    --}}


    {{--
        xNavTab()
        ->addTab("Home1")->setContent("aaa")
        ->addTab("Profile", $active=true)->setContent("bbb")
        ->addTab("Settings")->setContent("ccc")
    --}}

    {{--
    @php
        $formTabs = (new \Jiny\Table\FormBuilder($actions))->make();

        $navTab = xNavTab("nav-bordered");
        foreach ($formTabs as $tabname => $tabs) {

            $content = xDiv();
            foreach ($tabs as $tab) {
                $content->addItem($tab);
            }

            $navTab->addTab($tabname)->setContent($content);
        }
    @endphp

    {!! $navTab !!}
    --}}

    {!! xFormBuilder($actions, "nav-bordered") !!}


    {{--
    <x-navtab class="mb-3 nav-bordered">

            @foreach ($formTabs as $tabname => $tabs)
                <x-navtab-item class="" >
                    <x-navtab-link class="rounded-0">
                        <span class="d-none d-md-block">{{$tabname}}</span>
                    </x-navtab-link>


                    @foreach ($tabs as $tab)
                        {!! $tab !!}
                    @endforeach
                </x-navtab-item>
            @endforeach

    </x-navtab>
    --}}
</div>
