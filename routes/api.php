<?php

use App\Http\Controllers\BrandImageController;
use App\Http\Controllers\CommentAdminController;
use App\Http\Controllers\CommentReplyAdminController;
use App\Http\Controllers\ContactUsAdminController;
use App\Http\Controllers\DiscountAdminController;
use App\Http\Controllers\FaqAdminController;
use App\Http\Controllers\ApplyDiscountController;
use App\Http\Controllers\BankGatewayRequestController;
use App\Http\Controllers\BrandAdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryAdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PerfumeAdminController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\PerfumeBasedFactorController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\PerfumeImageController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ShoppingManagementController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ProductAdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\Perfume;
use App\Models\User;
use Dedoc\Scramble\Scramble;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Redis;

Route::get('/test',function() {
    $query = Perfume::query();

     $query->orWhere([['price','=',5231683],['volume','=',50]]);
     return $query->orWhere([['price','=',5231683],['volume','=',50]])->get();

});

require __DIR__.'/auth.php';
/* guest routes */
    // FAQ routes
    Route::get('/faq',[FaqController::class,'index']);

    // Contact us routes
    Route::post('/contact-us',[ContactUsController::class,'store']);

    // Brand routes
    Route::get('/brand',[BrandController::class,'index']);
    Route::get('brand/image/{brandImage}',[brandImageController::class,'show']);//show the images itself
    Route::get('/brand/logo/{brand:slug}',[BrandImageController::class,'showLogo']);//show the logo itself
    Route::get('/brand/{brand:slug}',[BrandController::class,'show']);

    // Category routes
    Route::get('/category',[CategoryController::class,'index']);
    Route::get('/category/{category:slug}',[CategoryController::class,'show']);

    // Product routes
    Route::get('/search',[PerfumeController::class,'index']);
    Route::get('/perfume/{perfume:slug}',[PerfumeController::class,'show']);

    // Perfume Comments and replies routes
    Route::get('perfume-comments/{id}',[CommentController::class,'show']);


    // Image routes
    Route::get('/perfume/image/{perfumeImage}',[PerfumeImageController::class,'show']);
      //route for public images like perfume , main page , banners , brand , category

    // Province routes
    Route::resource('province', ProvinceController::class)->only(['index','show']);

    // city routes
    Route::resource('city', CityController::class)->only(['index']);



/* user routes */
    Route::middleware(['auth:sanctum','customWebMiddleware','extendCartTime'])->group(function (){
        //complete his credential
        Route::get('/credentials',[CredentialController::class,'show']);
        Route::put('/credentials',[CredentialController::class,'update']);

        // Comment routes
        Route::post('perfume-comments/{perfume:slug}',[CommentController::class,'store']);
        Route::delete('perfume-comments/{perfumeComment}',[CommentController::class,'destroy']);

        // Reply routes
        Route::post('perfume-replies/{perfumeComment}',[CommentReplyController::class,'store']);
        Route::delete('perfume-replies/{perfumeReply}',[CommentReplyController::class,'destroy']);


        // shopping routes
        Route::get('/cart',[CartController::class,'index']);
        Route::post('/cart',[CartController::class,'store']);
        Route::delete('/cart',[CartController::class,'destroy']);
        Route::delete('flush-cart',[CartController::class,'destroyAll']);

        //discount cart route
        Route::get('/discount-card',[ApplyDiscountController::class,'index']);
        Route::post('/discount-card',[ApplyDiscountController::class,'store']);
        Route::delete('/discount-card/{inputDiscountCard}',[ApplyDiscountController::class,'destroy']);

        // shopping management route
        Route::get('/shopping-management',[ShoppingManagementController::class,'index']);

        // Buy routes
        Route::get('/bank-gateway',[BankGatewayRequestController::class,'show']);
    });




/* admin routes */
    Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum',ProductAdminMiddleware::class]], function () {


        // Province routes
        Route::put('province/{province}', [ProvinceController::class, 'update']);
        Route::delete('province/{province}', [ProvinceController::class, 'destroy']);


        //  City routes
        //todo put store route super admin
        Route::resource('city', CityController::class)->except(['index','show']);


        // FAQ routes
        Route::get('faq', [FaqAdminController::class, 'index']);
        Route::post('faq', [FaqAdminController::class, 'store']);
        Route::put('faq/{faq}', [FaqAdminController::class, 'update']);
        Route::delete('faq/{faq}', [FaqAdminController::class, 'destroy']);

        // Contact Us routes
        Route::get('/contact-us', [ContactUsAdminController::class, 'index']);
        Route::delete('/contact-us/{contactUs}', [ContactUsAdminController::class, 'destroy']);

        // Brand routes
        Route::get('brand', [BrandAdminController::class, 'index']);
        Route::get('brand/{slug}', [BrandAdminController::class, 'show']);
        Route::post('brand', [BrandAdminController::class, 'store']);
        Route::put('brand/{brand:slug}', [BrandAdminController::class, 'update']);
        Route::delete('brand/{brand:slug}', [BrandAdminController::class, 'destroy']);
        Route::post('brand/image/{brand:slug}', [BrandImageController::class, 'store']);
        Route::delete('brand/image/{brandImage}', [BrandImageController::class, 'destroy']);//delete one image of a brand
        Route::delete('/brand/images/{brand:slug}',[BrandImageController::class,'destroyAllImage']);//delete all image of a brand

        // Category routes
        Route::get('category', [CategoryAdminController::class, 'index']);
        Route::get('category/{slug}', [CategoryAdminController::class, 'show']);
        Route::post('category', [CategoryAdminController::class, 'store']);
        Route::put('category/{category:slug}', [CategoryAdminController::class, 'update']);
        Route::delete('category/{category:slug}', [CategoryAdminController::class, 'destroy']);

        // Product routes
        Route::get('/search',[PerfumeAdminController::class,'index']);
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
        Route::get('perfume-comments/{id}',[CommentAdminController::class,'show']);
        Route::delete('perfume-comments/{perfumeComment}',[CommentAdminController::class,'destroy']);

        // Reply routes
        Route::delete('perfume-replies/{perfumeCommentReply}',[CommentReplyAdminController::class,'destroy']);
        //todo maybe add delete by user (delete all comment of one user to avoid spam)
        //todo throttle for avoiding comment spam

        // Buy routes

        // Discount routes
        Route::resource('/discount-card',DiscountAdminController::class)->except(['update']);


        // Warranty routes

        // Image routes
        Route::post('/perfume/image/{perfume:slug}',[PerfumeImageController::class,'store']);
        Route::delete('/perfume/image/{perfumeImage}',[PerfumeImageController::class,'destroy']);//delete one image of a perfume
        Route::delete('perfume/images/{perfume:slug}',[PerfumeImageController::class,'destroyAllImage']);

        // User routes
        Route::get('/users',[UserController::class,'index']);
        Route::get('/users/{userId}',[UserController::class,'show']);
        Route::post('/users',[UserController::class,'store']);
        Route::delete('/users/{user}',[UserController::class,'destroy']);
    });

/* super admin routes */
Route::group(['prefix' => 'super-admin','middleware' => ['auth:sanctum',SuperAdminMiddleware::class]],function (){
    // Products based factor for super admin
    // get factors of specific admin

});
