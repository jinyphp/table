<?php

namespace Jiny\Table;

use Illuminate\Support\Facades\DB;
use \Jiny\Html\CTag;

class FormBuilder
{
    public $actions = [];
    public function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function xFormHorizontal($label, $input) {
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

    public function make($type=null)
    {
        $uri = "/".$this->actions['route']['uri'];
        $_forms = DB::table('table_forms')
            ->where('uri',$uri)
            ->where('enable',1)
            ->orderby('pos',"asc")->get();

        $formTabs = [];

        foreach ($_forms as $item) {
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

            $formTabs[$tab] []= $this->xFormHorizontal($item->label,$obj);
        }

        //return $formTabs;

        // 폼 텝출력
        $navTab = xNavTab($type);
        foreach ($formTabs as $tabname => $tabs) {

            $content = xDiv();
            foreach ($tabs as $tab) {
                $content->addItem($tab);
            }

            $navTab->addTab($tabname)->setContent($content);
        }

        return $navTab;
    }
}
