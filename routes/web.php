<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/images/public/{path}/{filename}',[\Jiny\Table\API\Controllers\ImageView::class,"index"])->name("images.public");

// 업로드한 이미지 보기
Route::middleware(['web','auth:sanctum', 'verified'])
->name('image.')
->prefix('/images')->group(function () {
    Route::get('/private/{path}/{filename}',[\Jiny\Table\API\Controllers\ImageView::class,"index"]);
});


// 관리자 URL
Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.table.')
->prefix('/admin/table')->group(function () {
    ## 동적 컬럼정보 읽기
    Route::resource('columns',\Jiny\Table\Http\Controllers\Admin\Columns::class);
    ## 동적 폼정보 읽기
    Route::resource('forms',\Jiny\Table\Http\Controllers\Admin\Forms::class);
    Route::resource('actions',\Jiny\Table\Http\Controllers\Admin\ActionsController::class);
    ## 설정
    Route::resource('setting', \Jiny\Table\Http\Controllers\Admin\SettingController::class);
    Route::post('column/drag',[\Jiny\Table\API\Controllers\ColumnsPos::class,"index"]);
});

Route::middleware(['web','auth:sanctum', 'verified'])
->prefix('/api')->group(function () {
    Route::post('table/column/pos',[\Jiny\Table\API\Controllers\ColumnsPos::class,"index"]);
    Route::post('table/column/resize',[\Jiny\Table\API\Controllers\ColumnsResize::class,"index"]);
    Route::post('table/forms/tabpos',[\Jiny\Table\API\Controllers\TabPos::class,"index"]);
    Route::post('table/forms/pos',[\Jiny\Table\API\Controllers\FormPos::class,"index"]);
});


// css modal popup 관리자
Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.forms.')
->prefix('/admin/forms')->group(function () {
    Route::post('/modal/popup', [\Jiny\Table\Http\Controllers\Modal\Popup::class,"create"]);
    Route::get('/modal/popup', [\Jiny\Table\Http\Controllers\Modal\Popup::class,"create"]);

    Route::get('/modal', function(){
        return view("jinytable::modal.popup");
    });

    Route::get('/modal/window', function(){
        return view("jinytable::modal.window");
    });
});
