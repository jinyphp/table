<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/images/public/{path}/{filename}',[\Jiny\Table\Http\Controllers\ImageView::class,"index"])->name("images.public");

Route::middleware(['web','auth:sanctum', 'verified'])
->name('image.')
->prefix('/images')->group(function () {

    Route::get('/private/{path}/{filename}',[\Jiny\Table\Http\Controllers\ImageView::class,"index"]);

});

