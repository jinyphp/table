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
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use Jiny\Table\Http\Livewire\WireTable;
class JsonTable extends WireTable
{

    // 오버라이딩
    protected function dataFetch($actions)
    {
        // table 필드를 source로 활용
        if(isset($actions['table']) && $actions['table']) {
            $source = $actions['table'];
        }

        if(isset($actions['source']) && $actions['source']) {
            $source = $actions['source'];
        }

        //$source = "https://jinytheme.github.io/store/themelist.json";
        if ($source) {
            if($pos = strpos($source,"://")) {
                $this->dataType = "uri";

                // url 리소스
                $response = HTTP::get($source);
                $body = $response->body();
                $json = json_decode($body);
                return $json->data;
            } else {
                $this->dataType = "file";

                // 파일 리소스
                $path = resource_path().$source;
                if (file_exists($path)) {
                    $json = file_get_contents($path);
                    $rows = json_decode($json)->data;
                    return $rows;
                }
            }
        }

        return [];
    }



}
