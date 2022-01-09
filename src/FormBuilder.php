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

        // 탭정보 읽기
        $tabs = DB::table('form_tabs')
            ->where('uri',$uri)
            ->where('enable',1)
            ->orderby('pos',"asc")->get();
        $tabRows = [];
        foreach($tabs as $tab) {
            $id = $tab->id;
            $tabRows[$id] = $tab;
        }



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


            $tabid = $item->tab;
            $tabname = $tabRows[$tabid]->name;
            if($tabname) {

            } else {
                $tabname = "basic";
            }

            $formTabs[$tabid] []= $this->xFormHorizontal($item->label,$obj);
        }



        // 폼 텝출력
        $navTab = xNavTab($type);

        foreach($tabRows as $item) {
            $tabid = $item->id;

            $content = xDiv();
            if(isset($formTabs[$tabid])) {
                foreach($formTabs[$tabid] as $form) {
                    $content->addItem($form);
                }
            }

            $navTab->setTab($item->name, $item->id)->setContent($content);
        }


        /*
        foreach ($formTabs as $tabid => $tabs) {

            $content = xDiv();
            foreach ($tabs as $tab) {
                $content->addItem($tab);
            }

            $tabname = $tabRows[$tabid]->name;
            //$navTab->addTab($tabname)->setContent($content);
            $navTab->setTab($tabname, $tabid)->setContent($content);
        }
        */


        return $navTab;
    }
}
