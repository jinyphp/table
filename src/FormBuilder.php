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

    public $drag = false;
    public function setDrag()
    {
        $this->drag = true;
    }

    public $type;
    public function setType($type)
    {
        $this->type;
    }

    /*
    public function xFormHorizontal($label, $input) {
        $rowDiv = new \Jiny\Html\CTag('div',true);

        $dragIcon = xSpan();
        $dragIcon->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
        </svg>');
        $rowDiv->addItem($dragIcon);



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
    */

    public function xFormHorizontal($label, $input) {

        $rowDiv = new \Jiny\Html\CTag('div',true);

        /*
        $dragIcon = xDiv();
        $dragIcon->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
        </svg>');
        //$dragIcon->addClass('-mt-8');
        $rowDiv->addItem($dragIcon);
        */


        // 타이틀 라벨
        $formLabel = xDiv();
            $xLabel = new \Jiny\Html\CTag('label',true);
            $xLabel->addItem($label);
            //$xLabel->addClass("col-sm-2");
            //$xLabel->addClass("form-label");
            $xLabel->addClass("m-0 font-light");
            $xLabel->addStyle("font-weight: normal;");
        $formLabel->addItem($xLabel);
        $formLabel->addStyle("width:150px");
        $formLabel->addClass("pr-2 text-right");
        $rowDiv->addItem($formLabel);


        // 필드입력
        $xCol = new \Jiny\Html\CTag('div',true);
        //$xCol->addClass("col-sm-10");
        $xCol->addItem($input);
        $rowDiv->addItem($xCol);


        // 도움말 버튼
        $helpIcon = xDiv();
        $helpIcon->addHtml('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
      </svg>');
        $helpIcon->addClass("px-2");
        $rowDiv->addItem($helpIcon);




        $rowDiv->addClass("flex items-center"); //tailwind

        return $rowDiv;
    }

    // 탭ID별 입력폼을 생성합니다.
    private function getForms($uri, $tabRows)
    {
        // 활성화된 폼 목록 확인하기
        $_forms = DB::table('table_forms')
            ->where('uri',$uri)
            ->where('enable',1)
            ->orderby('pos',"asc")->get();


        $formTabs = [];
        foreach ($_forms as $item) {
            // 폼타입을 확인합니다.
            if($item->input == "text") {
                $inputType = "xInputText";
            } else if($item->input == "checkbox") {
                $inputType = "xCheckbox";
            }

            // 폼을 생성합니다.
            if($inputType) {
                $obj = $inputType()
                    ->setWire('model.defer',"forms.".$item->field);
            }


            $tabid = $item->tab;
            $formRow = $this->xFormHorizontal($item->label,$obj);

            $li = (new CTag('li',true));
            $li->addClass("mb-2");
            //$li->style("list-style-type : none");
            $li->addClass('dragForms');
            $li->setAttribute("data-index",$item->id);
            $li->setAttribute("draggable","true");
            $li->addItem($formRow);

            $formTabs[$tabid] []= $li;


        }

        return $formTabs;
    }

    private function getTabs($uri)
    {
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

        return $tabRows;
    }


    public function make($type=null)
    {
        $uri = "/".$this->actions['route']['uri'];
        $tabRows = $this->getTabs($uri);
        $formTabs = $this->getForms($uri, $tabRows);


        // 폼 텝출력
        $navTab = xNavTab();
        foreach($tabRows as $item) {
            $tabid = $item->id;

            $content = (new CTag('ul',true));
            $content->addClass("from-group");
            $content->addClass("list-none");
            $content->addClass("p-0");
            $content->setAttribute('data-tab-index',$item->id);
            if(isset($formTabs[$tabid])) {
                // 탭요소 추가
                foreach($formTabs[$tabid] as $form) {
                    $form->setAttribute('data-tab-index', $tabid);
                    $content->addItem($form);
                }
            }

            // 텝그룹 이동셀 zone
            $li = (new CTag('li',true));
            $li->addClass("mb-2");
            $li->addClass('dragForms');
            $li->setAttribute("data-index",999);
            $li->setAttribute("draggable","true");
            $li->addItem("여기에 드롭하면 ".$item->name." 텝으로 이동됩니다.");
            $li->addClass("hidden p-8 text-center bg-gray-100 tab-move-zone");
            $content->addItem($li);



            $navTab->setTab([
                'label'=>$item->name,
                $this->btnSetting($tabid)
            ], $content, $drag=$tabid);
        }

        // 동적 추가
        $navTab->setTab(['label'=>$this->btnAddPlus()], "새로운 탭을 추가합니다.");

        return $navTab;
    }

    /** ----- ----- ----- ----- -----
     *
     */

    public function btnSetting($id)
    {
        $setting = xSpan();
        $setting->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
        </svg>');
        //->addClass("px-2");

        $link = (new CTag('a',true))
                    ->addItem($setting)
                    ->setAttribute('href',"javascript: void(0);");
        $link->setAttribute('wire:click', "popupTabEdit('".$id."')");
        // $link->addClass("nav-link"); // bootstrap

        return $link;
    }

    public function btnAddPlus()
    {
        // 설정버튼 (bootstrap)
        $setting = xSpan();
        $setting->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
      </svg>')->addClass("px-2");

        $link = (new CTag('a',true))
                    ->addItem($setting)
                    ->setAttribute('href',"javascript: void(0);");
        $link->setAttribute('wire:click', "popupNewTab");
        return $link;
    }
}
