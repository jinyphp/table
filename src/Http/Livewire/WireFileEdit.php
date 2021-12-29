<?php
/**
 *
 */
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class WireFileEdit extends Component
{
    use \Jiny\Table\Http\Livewire\Permit;
    public $actions;

    public function mount()
    {
        $this->permitCheck();
    }

    public function render()
    {
        return view("jinytable::livewire.popup.form");
    }

    /**
     * 팝업창 관리
     */
    protected $listeners = ['popupFormOpen','popupFormClose','popupFormCreate','edit','create'];
    public $popupForm = false;
    public function popupFormOpen()
    {
        $this->popupForm = true;
        $this->confirm = false;
    }

    public function popupFormClose()
    {
        $this->popupForm = false;
        $this->filename = "";
    }

    /**
     * 수정
     */
    public $filename;
    public $body;

    public function create($path)
    {
        if($this->permit['create']) {

            unset($this->actions['id']);
            $this->loadViewFile($path);

            $this->popupFormOpen();

        } else {
            $this->popupPermitOpen();
        }
    }

    public function edit($path)
    {
        if($this->permit['update']) {
            $this->actions['id'] = $path;

            $this->loadViewFile($path);
            $this->popupFormOpen();

        } else {
            $this->popupPermitOpen();
        }
    }

    private function loadViewFile($path)
    {
        if($path) {
            $filename = str_replace("\\",DIRECTORY_SEPARATOR,$path);
            if(file_exists($this->actions['file_path'].$filename) &&
                is_file($this->actions['file_path'].$filename)) {
                $this->body = file_get_contents($this->actions['file_path'].$filename);

                $this->filename = $filename;
            } else {
                $this->filename = $filename."/";
            }
        } else {
            $this->filename = "/";
        }

        $this->filename = str_replace("\\","/",$this->filename);
    }


    public function store()
    {
        if($this->permit['create']) {

            $this->save();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');
        } else {
            $this->popupPermitOpen();
        }
    }

    public function update()
    {
        if($this->permit['update']) {

            $this->save();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');
        } else {
            $this->popupPermitOpen();
        }
    }

    private function save()
    {
        if($this->filename && $this->filename != "/") {
            $this->filename = str_replace("/",DIRECTORY_SEPARATOR,$this->filename);

            //dd($this->body);

            file_put_contents($this->actions['file_path'].$this->filename, $this->body);
            $this->popupFormClose();
        }
    }

    /**
     * 데이터 삭제 동작
     * 삭제는 2단계로 동작합니다. 삭제 버튼을 클릭하면, 실제 동작 버튼이 활성화 됩니다.
     */

    public $confirm = false;

    public function deleteConfirm()
    {
        if($this->permit['delete']) {
            $this->confirm = true;
        } else {
            $this->popupFormClose();
            $this->popupPermitOpen();
        }
    }

    public function delete()
    {
        if($this->permit['delete']) {
            if($this->filename && $this->filename != "/") {
                $this->filename = str_replace("/",DIRECTORY_SEPARATOR,$this->filename);
                if(file_exists($this->actions['file_path'].$this->filename)) {
                    unlink($this->actions['file_path'].$this->filename);
                    $this->filename = "";
                }
            }

            // 팝업창 닫기
            $this->popupFormClose();

            // Livewire Table을 갱신을 호출합니다.
            $this->emit('refeshTable');

        } else {
            $this->popupFormClose();
            $this->popupPermitOpen();
        }
    }
}
