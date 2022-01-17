<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Table\Http\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DesignForm extends Component
{
    use \Jiny\Table\Http\Livewire\Permit;

    public $action_path;
    public $actions=[];
    public $forms=[];

    public function mount()
    {
        $this->permitCheck();
    }

    public function render()
    {
        if($this->action_path) {
            $path = resource_path("actions");
            $filename = $path.DIRECTORY_SEPARATOR.$this->action_path.".json";
            if (file_exists($filename)) {
                $json = file_get_contents($filename);
                $this->actions = json_decode($json, true);
            }
        }

        return view('jinytable::livewire.design.form');
    }

    protected $listeners = ['popupDesignOpen','popupDesignClose','popupDesignCreate'];
    public $popupDesgin = false;

    public function popupDesignOpen()
    {
        $this->popupDesgin = true;
    }

    public function popupDesignClose()
    {
        $this->popupDesgin = false;
    }

    public function popupDesignCreate($action, ...$args)
    {
        $this->action_path = $action;

        $this->popupDesignOpen();
        //$aaa = [$action, $this->action_path, $args];
        //dd($args[0]);
        $this->forms = json_decode($args[0],true);
        //dd($forms);

    }

    public function popupDesignStore()
    {
        if($this->permit['create']) {

            // 1.유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
            }

            // 2. 시간정보 생성
            $this->forms['created_at'] = date("Y-m-d H:i:s");
            $this->forms['updated_at'] = date("Y-m-d H:i:s");

            // 3. 파일 업로드 체크
            //$this->fileUpload();

            // 4. 컨트롤러 메서드 호출
            /*
            if ($controller = $this->isHook("hookStoring")) {
                $form = $controller->hookStoring($this, $this->forms);
            } else {
                $form = $this->forms;
            }
            */
            $form = $this->forms;

            // 5. 데이터 삽입
            if($form) {
                $id = DB::table($this->actions['table'])->insertGetId($form);
                $this->forms['id'] = $id;

                // 6. 컨트롤러 메서드 호출
                /*
                if ($controller = $this->isHook("hookStored")) {
                    $controller->hookStored($this, $this->forms);
                }
                */
            }

            /*
            // 입력데이터 초기화
            $this->cancel();

            // 팝업창 닫기
            $this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');
            */

            $this->popupDesignClose();

        } else {
            $this->popupPermitOpen();
        }
    }
}
