<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        //get only phone number and sms token
        //TODO validate the phone verify code
//        if (!isValidPhoneVerifyCode($phoneNumber, $phoneVerifyCode)) {
//            return response()->json(['error' => 'Invalid verification code'], 422);
//        }
        $phoneNumber = $request->input('phone_number');
        $phoneVerifyCode = $request->input('phone_verify_code');
        //if the user is not registered
        $user = User::firstOrCreate(['phone_number' => $phoneNumber]);
        $token = $user->createToken('accessToken');
        return ['token' => $token->plainTextToken];
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
