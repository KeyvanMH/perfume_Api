<?php

use App\Http\Controllers\AdminContactUsController;
use App\Http\Controllers\AdminFaqController;
use App\Http\Controllers\BrandAdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

//route for users
Route::middleware(['auth:sanctum'])->group(function (){

});

// admin route
//Route::middleware(['auth:sanctum','ProductAdmin'])->group(function () {
Route::middleware(['auth:sanctum'])->group(function () {
//TODO make productAdmin middleware
    Route::get('admin/faq',[AdminFaqController::class,'index']);
    Route::post('admin/faq',[AdminFaqController::class,'store']);
    Route::put('admin/faq/{faq}',[AdminFaqController::class,'update']);
    Route::delete('admin/faq/{faq}',[AdminFaqController::class,'destroy']);
    /* contact us -> status : all , active , deleted */
    Route::get('/admin/contact-us/{status}',[AdminContactUsController::class,'index']);
    Route::delete('/admin/contact-us/{contactUs}',[AdminContactUsController::class,'destroy']);
    /* Brand*/
    Route::get('/admin/brand',[BrandAdminController::class,'index']);
    Route::get('/admin/brand/{slug}',[BrandAdminController::class,'show']);
    Route::post('/admin/brand',[BrandAdminController::class,'store']);
    Route::put('/admin/brand/{brand:slug}',[BrandAdminController::class,'update']);
    Route::delete('admin/brand/image',[BrandAdminController::class,'destroyImage']);
    Route::delete('/admin/brand/{brand:slug}',[BrandAdminController::class,'destroy']);
    Route::post('admin/brand/image/{brand:slug}',[BrandAdminController::class,'storeImage']);
});

//guest routes
/* faq  */
Route::get('/faq',[FaqController::class,'index']);

/* contact us form */
Route::post('/contact-us',[ContactUsController::class,'store']);

/* Brand */
Route::get('/brand',[BrandController::class,'index']);
Route::get('/brand/{brand:slug}',[BrandController::class,'show']);

/* Product */
Route::get('/product',);


