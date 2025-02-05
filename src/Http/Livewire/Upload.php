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
    public function setUploadPublic()
    {
        // public 폴더에 업로드 파일을 저장합니다.
        $this->actions['upload']['visible'] = "public";
    }

    public function setUploadPath($path)
    {
        $this->actions['upload']['path'] = $path;
    }


    private function checkUploadPath()
    {
        // public or private 저장영역 설정
        if(isset($this->actions['upload']['visible'])) {
            $visible = $this->actions['upload']['visible'];
        } else {
            $visible = "private";
        }

        if(isset($this->actions['upload']['path'])) {
            $uploadPath = $this->actions['upload']['path'];
        } else {
            // 업로드 경로가 없는경우, 테이블명으로 경로를 지정합니다.
            $uploadPath = $this->actions['table']['name'];
        }

        if($visible=="public") {
            $appPath = public_path();
            $path = $appPath.DIRECTORY_SEPARATOR.$uploadPath;
            if(!\is_dir($path)) {
                \mkdir($path, 755, true);
            }
        } else {
            // 저장소 폴더 확인
            $appPath = storage_path('app/'.$visible);
            $path = $appPath.DIRECTORY_SEPARATOR.$uploadPath;
            if(!\is_dir($path)) {
                \mkdir($path, 755, true);
            }
        }

        return $path;
    }

    public function fileUpload()
    {
        $path = $this->checkUploadPath();

        foreach($this->forms as $key => $item) {
            if($item instanceof \Livewire\TemporaryUploadedFile) {

                //$filename = $item->store($upload_directory, $visible);
                $filename = $item->store("upload");
                $filename = substr($filename, strrpos($filename,'/')+1);

                $filePath = storage_path('app/upload').DIRECTORY_SEPARATOR.$filename;
                $movePath = $path."/".$filename;

                if(isset($this->actions['upload']['path'])) {
                    $this->forms[$key] = "/".$this->actions['upload']['path']."/".$filename;
                } else {
                    $this->forms[$key] = "/".$this->actions['table']['name']."/".$filename;
                }

                // 실제 경로로 이동
                if(file_exists($filePath)) {
                    //dd($filePath);
                    rename($filePath, $movePath);
                }

                // uploadfile 테이블에 기록
                DB::table('uploadfile')->updateOrInsert([
                    'table' => $this->actions['table']['name'],
                    'field' => $key
                ]);

            }
        }

    }




    private function checkEditUploadFile($origin)
    {
        // File필드만 검출
        foreach($this->forms as $key => $item) {
            if($item instanceof \Livewire\TemporaryUploadedFile) {

            }
        }

        // uploadfile 필드 조회
        /*
        $fields = DB::table('uploadfile')->where('table', $this->actions['table']['name'])->get();
        foreach($fields as $item) {
            $key = $item->field; // 업로드 필드명
            if($origin->$key != $this->forms[$key]) {
                ## 이미지를 수정하는 경우, 기존 이미지는 삭제합니다.
                // Storage::delete($origin->$key);
            }
        }
        */
    }

}
