<?php
/**
 * Upload를 처리합니다.
 */
namespace Jiny\Table\Http\Livewire;
use Illuminate\Support\Facades\DB;
trait Upload
{
    /** ----- ----- ----- ----- -----
     *  파일 업로드
     */

    public function fileUpload()
    {
        // 테이블명과 동일한 폴더에 저장
        $upload_directory = $this->actions['table'];

        // public or private 저장영역 설정
        if(isset($this->actions['visible'])) {
            $visible = $this->actions['visible'];
        } else {
            $visible = "private";
        }

        $appPath = storage_path('app/'.$visible);
        $path = $appPath.DIRECTORY_SEPARATOR.$upload_directory;
        if(!\is_dir($path)) {
            \mkdir($path, 755, true);
        }

        foreach($this->forms as $key => $item) {
            if($item instanceof \Livewire\TemporaryUploadedFile) {
                $filename = $item->store($upload_directory, $visible);
                $this->forms[$key] = $visible."/".$filename;

                // uploadfile 테이블에 기록
                DB::table('uploadfile')->updateOrInsert([
                    'table' => $this->actions['table'],
                    'field' => $key
                ]);

            }
        }

    }
}
