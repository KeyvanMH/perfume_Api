<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\SmsRequest;
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
        $validatedData = $request->validated();

        $phoneNumber = $validatedData['phone_number'];
        $phoneVerifyCode = $validatedData['phone_verify_code'] ?? null;
        $password = $validatedData['password'] ?? null;


        // Check for invalid input
        if (!$phoneVerifyCode and !$password) {
            return response()->json(['response' => 'ورودی نامعتبر'], 404);
        }

        // Validate the password if provided
        if ($password) {
            if (!Auth::attempt(['phone_number' => $phoneNumber, 'password' => $password])) {
                return response()->json(['response' => 'ورودی نامعتبر']);
            }
        } elseif ($phoneVerifyCode) {
            // Get the latest SMS verification for the phone number
            $dbPhoneNumber = SmsRequest::where('phone_number', $phoneNumber)->latest()->first();

            // Check if the SMS verification code is valid
            if (empty($dbPhoneNumber) or $dbPhoneNumber->code != $phoneVerifyCode) {
                return response()->json(['response' => 'ورودی نامعتبر']);
            }
        }

        // If the user is not registered, create a new user
        $user = User::firstOrCreate(['phone_number' => $phoneNumber]);
        $token = $user->createToken('accessToken');

        return response()->json(['token' => $token->plainTextToken]);

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
