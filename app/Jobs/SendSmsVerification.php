<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendSmsVerification implements ShouldQueue
{
    use Queueable;
    protected $phoneNumber;
    protected $verificationCode;

    /**
     * Create a new job instance.
     */
    public function __construct($phoneNumber,$verificationCode)
    {
        $this->phoneNumber = $phoneNumber;
        $this->verificationCode = $verificationCode;    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Replace with your SMS service API logic
        Http::post('https://sms-api.example.com/send', [
            'to' => $this->phoneNumber,
            'message' => "Your verification code is: {$this->verificationCode}",
        ]);    }
}
