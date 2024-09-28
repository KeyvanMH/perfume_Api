<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsRequestRequest;
use App\Jobs\SendSmsVerification;
use App\Models\SmsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsVerificationController extends Controller
{
    public function SmsRequest(SmsRequestRequest $request) {
        //check if sender is already a user
        if (Auth::check()) {
            return response()->json([
                'message' => 'شما قبلاً احراز هویت شده‌اید و نمی‌توانید کد تأیید پیامک جدیدی درخواست کنید'
            ], 403);
        }
        //check for sms rateLimiting
        $recentRequest = SmsRequest::where([['phone_number','=',$request->validated('phone_number')],['created_at','>=',Carbon::now()->subMinute(2)]])
            ->get();
        if(!empty($recentRequest)){
            $timeDiff = Carbon::now()->diffInSeconds($recentRequest->created_at);
            $remainingTime = 120 - $timeDiff;
            return response()->json(['response' => 'بعد از  '.$remainingTime.'ثانیه امتحان کنید'],429);
        }

        $number = rand(10000,99999);
        SmsRequest::create([
            'phone_number' => $request->validated('phone_number'),
//            'code' => $number
            'code' => 11111
        ]);
//        SendSmsVerification::dispatch($request->validated('phone_number'), $number);
        return response()->json(['response' => 'کد ارسال شد'],200);
    }
}

