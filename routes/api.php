<?php

use App\Http\Controllers\AdminContactUsController;
use App\Http\Controllers\AdminFaqController;
use App\Http\Controllers\BrandAdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryAdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PerfumeAdminController;
use App\Http\Controllers\PerfumeController;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/test',function (Request $request){
    return $request->user()->id;
})->middleware('auth:sanctum');

/* guest routes */
    // FAQ routes
    Route::get('/faq',[FaqController::class,'index']);

    // Contact us routes
    Route::post('/contact-us',[ContactUsController::class,'store']);

    //Brand routes
    Route::get('/brand',[BrandController::class,'index']);
    Route::get('/brand/{brand:slug}',[BrandController::class,'show']);

    // Category routes
    Route::get('/category',[CategoryController::class,'index']);
    Route::get('/category/{category:slug}',[CategoryController::class,'show']);

    // Product routes
    Route::get('search/',[PerfumeController::class,'index']);
    Route::get('products/{product:slug}',[PerfumeController::class,'show']);

    // Comment routes

    // Reply routes


/* user routes */
    Route::middleware(['auth:sanctum'])->group(function (){

        // Comment routes

        // Reply routes

        // Buy routes

    });




/* admin routes */
    //TODO add admin middleware
    Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
        // FAQ routes
        Route::get('faq', [AdminFaqController::class, 'index']);
        Route::post('faq', [AdminFaqController::class, 'store']);
        Route::put('faq/{faq}', [AdminFaqController::class, 'update']);
        Route::delete('faq/{faq}', [AdminFaqController::class, 'destroy']);

        // Contact Us routes
        Route::get('contact-us', [AdminContactUsController::class, 'index']);
        Route::delete('contact-us/{contactUs}', [AdminContactUsController::class, 'destroy']);

        // Brand routes
        Route::get('brand', [BrandAdminController::class, 'index']);
        Route::get('brand/{slug}', [BrandAdminController::class, 'show']);
        Route::post('brand', [BrandAdminController::class, 'store']);
        Route::put('brand/{brand:slug}', [BrandAdminController::class, 'update']);
        Route::delete('brand/image', [BrandAdminController::class, 'destroyImage']);
        Route::delete('brand/{brand:slug}', [BrandAdminController::class, 'destroy']);
        Route::post('brand/image/{brand:slug}', [BrandAdminController::class, 'storeImage']);

        // Category routes
        Route::get('category', [CategoryAdminController::class, 'index']);
        Route::get('category/{slug}', [CategoryAdminController::class, 'show']);
        Route::post('category', [CategoryAdminController::class, 'store']);
        Route::put('category/{category:slug}', [CategoryAdminController::class, 'update']);
        Route::delete('category/{category:slug}', [CategoryAdminController::class, 'destroy']);

        // Product routes
        Route::get('search/{query}',[PerfumeAdminController::class,'index']);
        Route::get('product/{slug}',[PerfumeAdminController::class,'show']);
        Route::post('product',[PerfumeAdminController::class,'store']);
        Route::put('product/{perfume:slug}',[PerfumeAdminController::class,'update']);
        Route::delete('product/{perfume:slug}',[PerfumeAdminController::class,'destroy']);

        // Product based factor routes

        // Factor routes

        // Comment routes

        // Reply routes

        // Buy routes

        // Discount routes

        // Warranty routes
    });
