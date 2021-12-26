<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/images/public/{path}/{filename}',[\Jiny\Table\Http\Controllers\ImageView::class,"index"])->name("images.public");

Route::middleware(['web','auth:sanctum', 'verified'])
->name('image.')
->prefix('/images')->group(function () {

    Route::get('/private/{path}/{filename}',[\Jiny\Table\Http\Controllers\ImageView::class,"index"]);

});


// 관리자 URL
Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.table.')
->prefix('/admin/table')->group(function () {

    Route::resource('actions',\Jiny\Table\Http\Controllers\Admin\ActionsController::class);

    ## 설정
    Route::resource('setting', \Jiny\Table\Http\Controllers\Admin\SettingController::class);

});
