<?php

use App\Http\Controllers\AdminContactUsController;
use App\Http\Controllers\AdminFaqController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
//route for users
Route::middleware(['auth:sanctum'])->group(function (){

});
//guest routes
/* faq  */
Route::get('/faq',[FaqController::class,'index']);

/* contact us form */
Route::post('/contact-us',[ContactUsController::class,'store']);

// admin route
//Route::middleware(['auth:sanctum','ProductAdmin'])->group(function () {
Route::middleware(['auth:sanctum'])->group(function () {
//TODO make productAdmin middleware
    Route::get('admin/faq',[AdminFaqController::class,'index']);
    Route::post('admin/faq',[AdminFaqController::class,'store']);
    Route::put('admin/faq/{faq}',[AdminFaqController::class,'update']);
    Route::delete('admin/faq/{faq}',[AdminFaqController::class,'destroy']);
    //route for contact us
    /* status : all , active , deleted */
    Route::get('/admin/contact-us/{status}',[AdminContactUsController::class,'index']);
    Route::delete('/admin/contact-us/{contactUs}',[AdminContactUsController::class,'destroy']);
});



