<?php
/**
 * 파일목록 출력하기
 */
namespace Jiny\Table\Http\Livewire;

use Livewire\Component;

class WireFiles extends Component
{
    use \Jiny\Table\Http\Livewire\Permit;
    public $actions;

    public function mount()
    {
        $this->permitCheck();
    }

    public function render()
    {
        if($this->permit['read']) {
            if(isset($this->actions['file_path'])) {
                if(isset($this->actions['except_files'])) {
                    $this->exceptDir = array_merge($this->exceptDir, $this->actions['except_files']);
                }

                //$path = resource_path("views");
                $path = $this->actions['file_path'];
                $rows = $this->scanDirAll($path);

                return view($this->actions['view_list'],[
                    'rows'=>$rows
                ]);

            } else {
                return view('jinytable::error.message',['message'=>"파일 경로가 없습니다."]);
            }
        }

        // 권한 접속 실패
        return view("jinytable::error.permit",[
            'actions'=>$this->actions
        ]);
    }

    private $exceptDir = ['.','..','.git'];
    private function scanDirAll($path)
    {
        $resource_path = $this->actions['file_path']; //resource_path("views");

        $files = [];
        foreach (scandir($path) as $item) {
            foreach($this->exceptDir as $name) {
                if($item == $name) continue(2);
            }

            $_path = str_replace($resource_path,"",$path.DIRECTORY_SEPARATOR.$item);

            if(is_dir($path.DIRECTORY_SEPARATOR.$item)) {
                $files []= [
                    'name'=>$item,
                    'path'=>$_path,
                    'dir'=>$this->scanDirAll($path.DIRECTORY_SEPARATOR.$item)
                ];
            } else {
                $files []= [
                    'name'=>$item,
                    'path'=>$_path,
                ];
            }
        }

        return $files;
    }

    protected $listeners = ['refeshTable'];
    public function refeshTable()
    {
        ## 페이지를 재갱신 합니다.
    }

}
