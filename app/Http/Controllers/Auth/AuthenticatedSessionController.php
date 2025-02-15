<?php

namespace App\Http\Controllers\Auth;

use App\Http\Const\DefaultConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\SmsRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Post(
 *       path="/api/login",
 *       summary="میتوانید با کد ارسالی یا پسورد لاگین و رجیستر کنید",
 *      @OA\RequestBody(
 *           @OA\MediaType(
 *               mediaType="application/json",
 *               @OA\Schema(
 *                   @OA\Property(
 *                       property="phone_number",
 *                       type="string",
 *                   ),
 *                   @OA\Property(
 *                       property="phone_verify_code",
 *                       type="string"
 *                   ),
 *                   @OA\Property(
 *                       property="password",
 *                       type="string"
 *                   ),
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *            response=201,
 *            description="OK"
 *            ),
 *       @OA\Response(
 *            response=403,
 *            description="unAuthorized"
 *            ),
 *        )
 *   )
 **/

class   AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        //if he is already authenticated
        $validatedData = $request->validated();

        $phoneNumber = $validatedData['phone_number'];
        $phoneVerifyCode = $validatedData['phone_verify_code'] ?? null;
        $password = $validatedData['password'] ?? null;


        // Check for invalid input
        if (!$phoneVerifyCode and !$password) {
            return response()->json(['response' =>DefaultConst::INVALID_INPUT], 404);
        }

        // Validate the password if provided
        if ($password) {
            if (!Auth::attempt(['phone_number' => $phoneNumber, 'password' => $password])) {
                return response()->json(['response' =>DefaultConst::INVALID_INPUT]);
            }
        } elseif ($phoneVerifyCode) {
            // Get the latest SMS verification for the phone number
            $dbPhoneNumber = SmsRequest::where('phone_number', $phoneNumber)->latest()->first();

            // Check if the SMS verification code is valid
            if (empty($dbPhoneNumber) or $dbPhoneNumber->code != $phoneVerifyCode) {
                return response()->json(['response' => DefaultConst::INVALID_INPUT]);
            }
        }

        // If the user is not registered, create a new user
        $user = User::firstOrCreate(['phone_number' => $phoneNumber]);
//        $token = $user->createToken('accessToken',expiresAt: Carbon::now()->addHours(12));
        $token = $user->createToken('accessToken',expiresAt: null);

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
