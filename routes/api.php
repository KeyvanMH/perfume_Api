<?php

use App\Http\Controllers\AdminFaqController;
use App\Http\Controllers\FaqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('admin/faq',[FaqController::class,'index']);
});
//guest routes
Route::get('/faq',[FaqController::class,'index']);

// admin route
Route::middleware(['auth:sanctum','ProductAdmin'])->group(function () {
//TODO make productAdmin middleware
    Route::get('admin/faq',[AdminFaqController::class,'index']);
    Route::post('admin/faq',[AdminFaqController::class,'store']);
    Route::put('admin/faq/{id}',[AdminFaqController::class,'update']);
    Route::delete('admin/faq/{id}',[AdminFaqController::class,'destroy']);
});



