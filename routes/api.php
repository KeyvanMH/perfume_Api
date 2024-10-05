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
use App\Http\Controllers\FactorController;
use App\Http\Controllers\PerfumeBasedFactorController;
use App\Http\Controllers\PerfumeController;
use App\Http\Middleware\ProductAdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

require __DIR__.'/auth.php';
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
    Route::get('/search/',[PerfumeController::class,'index']);
    Route::get('/perfume/{perfume:slug}',[PerfumeController::class,'show']);

    // Comment routes


    // Reply routes

    // Image routes
      //route for public images like perfume , main page , banners , brand , category


/* user routes */
    Route::middleware(['auth:sanctum'])->group(function (){


        //complete his credential

        // Comment routes

        // Reply routes

        // Buy routes

    });




/* admin routes */
    Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum',ProductAdminMiddleware::class]], function () {
        // FAQ routes
        Route::get('faq', [AdminFaqController::class, 'index']);
        Route::post('faq', [AdminFaqController::class, 'store']);
        Route::put('faq/{faq}', [AdminFaqController::class, 'update']);
        Route::delete('faq/{faq}', [AdminFaqController::class, 'destroy']);

        // Contact Us routes
        Route::get('/contact-us', [AdminContactUsController::class, 'index']);
        Route::delete('/contact-us/{contactUs}', [AdminContactUsController::class, 'destroy']);

        // Brand routes
        Route::get('brand', [BrandAdminController::class, 'index']);
        Route::get('brand/{slug}', [BrandAdminController::class, 'show']);
        Route::post('brand', [BrandAdminController::class, 'store']);
        Route::put('brand/{brand:slug}', [BrandAdminController::class, 'update']);
        Route::delete('brand/image/{brandImage}', [BrandAdminController::class, 'destroyImage']);
        Route::delete('brand/{brand:slug}', [BrandAdminController::class, 'destroy']);
        Route::post('brand/image/{brand:slug}', [BrandAdminController::class, 'storeImage']);

        // Category routes
        Route::get('category', [CategoryAdminController::class, 'index']);
        Route::get('category/{slug}', [CategoryAdminController::class, 'show']);
        Route::post('category', [CategoryAdminController::class, 'store']);
        Route::put('category/{category:slug}', [CategoryAdminController::class, 'update']);
        Route::delete('category/{category:slug}', [CategoryAdminController::class, 'destroy']);

        // Product routes
        Route::get('/search/{query}',[PerfumeAdminController::class,'index']);
        Route::get('/perfume/based-factor/{slug}',[PerfumeAdminController::class,'indexBasedFactor']);
        Route::get('/perfume/{slug}',[PerfumeAdminController::class,'show']);
        Route::post('/perfume',[PerfumeAdminController::class,'store']);
        Route::put('/perfume/{perfume:slug}',[PerfumeAdminController::class,'update']);
        Route::delete('/perfume/{perfume:slug}',[PerfumeAdminController::class,'destroy']);

        // Factor routes
        Route::get('/factor',[FactorController::class,'index']);
        Route::get('/factor/{id}',[FactorController::class,'show']);
        Route::post('/factor',[FactorController::class,'store']);
        Route::delete('/factor/{factor}',[FactorController::class,'destroy']);
        Route::get('/factor/personal/{user}',[FactorController::class,'indexAdminFactor']);

        // Product based factor
        Route::get('/factor-product/{id}',[PerfumeBasedFactorController::class,'show']);
        Route::put('/factor-product/{id}',[PerfumeBasedFactorController::class,'update']);
        Route::delete('/factor-product/{id}',[PerfumeBasedFactorController::class,'destroy']);

        // Sold routes

        // Comment routes

        // Reply routes

        // Buy routes

        // Discount routes

        // Warranty routes
    });

/* super admin routes */
Route::group(['prefix' => 'super-admin','middleware' => ['auth:sanctum',SuperAdminMiddleware::class]],function (){
    // Products based factor for super admin
    // get factors of specific admin

});
