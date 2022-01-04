<?php
/**
 *
 */
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class WireConfig extends Component
{
    use \Jiny\Table\Http\Livewire\Permit;

    public $actions;
    public $filename;

    public $back=false;
    public $url;

    public $form=[];

    public function mount()
    {
        $this->permitCheck();
        $this->configLoading();
    }

    private function configLoading()
    {
        if(isset($this->actions['filename'])) {
            $this->filename = $this->actions['filename'];
        }

        if ($this->filename) {
            $path = config_path().DIRECTORY_SEPARATOR.$this->filename.".php";
            if (file_exists($path)) {
                $this->form = config($this->filename);
            }
        }
    }

    public function render()
    {
        // 컨트롤러 메서드 호출
        if(isset($this->actions['controller'])) {
            $controller = $this->actions['controller']::getInstance($this);
            if(method_exists($controller, "hookCreating")) {
                $controller->hookCreating($this);
            }
        }

        return view("jinytable::livewire.form");
    }


    public function submit()
    {
        $this->store();
        $this->goToIndex();
    }

    /**
     * 신규저장
     */
    public function store()
    {
        if($this->permit['create'] || $this->permit['update']) {
            //유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->form, $this->actions['validate'])->validate();
            }

            #// $this->form['created_at'] = date("Y-m-d H:i:s");
            $this->form['updated_at'] = date("Y-m-d H:i:s");

            // 컨트롤러 메서드 호출
            if(isset($this->actions['controller'])) {
                $controller = $this->actions['controller']::getInstance($this);
                if(method_exists($controller, "hookStoring")) {
                    $form = $controller->hookStoring($this->form);
                } else {
                    $form = $this->form;
                }
            } else {
                $form = $this->form;
            }

            // 설정값을 파일로 저장
            if ($this->filename) {
                $file = $this->convToPHP($form);
                $path = config_path().DIRECTORY_SEPARATOR.$this->filename.".php";
                file_put_contents($path, $file);
            }

        } else {
            $this->popupPermitOpen();

            // 다시 데이터 로딩...
            $this->configLoading();
        }
    }

    public function convToPHP($form)
    {
        $str = json_encode($form);

        // php 배열형태로 변환
        $str = str_replace('{',"[\r\n",$str);
        $str = str_replace('}',"\r\n]",$str);
        $str = str_replace('":',"\"=>",$str);
        $str = str_replace(',',",\r\n",$str);

        // php 파일
        $file = "<?php
return ".$str.";";

        return $file;
    }

    public function clear()
    {
        $this->form = [];
    }

    private function goToIndex()
    {
        if ($this->back && $this->url) {
            return redirect()->route($this->url);
        }
    }

}
