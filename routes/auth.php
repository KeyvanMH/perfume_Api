<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\SmsVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // route for regular user
    // TODO for logout we should remove the access token
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    // TODO route for completing info of the user
    //    Route::post('complete-info',);
});

// route for guests
// Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//    ->middleware(['signed', 'throttle:6,1'])
//    ->name('verification.verify');

// Sms Verification route
Route::post('/sms-request', [SmsVerificationController::class, 'SmsRequest'])
    ->middleware(['throttle:1,2']);

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

// TODO change reset password from from email to phone number
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
