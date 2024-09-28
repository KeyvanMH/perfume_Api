<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SmsVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsVerificationController;

Route::middleware(['auth:sanctum'])->group(function (){
    //route for regular user
    //TODO for logout we should remove the access token
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    //TODO route for completing info of the user
//    Route::post('complete-info',);
});

//route for guests
//TODO this route must be for login with sms
//TODO if the user is already authenticated , dont let to send sms request
//Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//    ->middleware(['signed', 'throttle:6,1'])
//    ->name('verification.verify');

//TODO this route is for sending sms request
// Sms Verification route
Route::post('/sms-request',[SmsVerificationController::class,'SmsRequest'])
    ->middleware(['throttle:1,2']);

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');
//TODO maybe add route for logging with password

//TODO change reset password from from email to phone number
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');




