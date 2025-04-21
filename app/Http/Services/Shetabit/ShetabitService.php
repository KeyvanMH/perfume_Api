<?php

namespace App\Http\Services\Shetabit;

use App\Exceptions\ErrorException;
use App\Http\Services\SoldProduct\SoldProductService;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class ShetabitService
{
    public string $bankGatewayUrl;

    public string $transactionId;

    protected object $soldProductService;

    protected string $callbackUrl;

    protected string $reference_id;

    public function __construct(SoldProductService $soldProductService, string $callbackUrl)
    {
        $this->soldProductService = $soldProductService;
        $this->callbackUrl = $callbackUrl;
    }

    public function pay($user, array $usersCartStatus)
    {
        $invoice = new Invoice;
        // sandbox only works with 1000 amount ( i think :) )
        env('APP_ENV') == 'production' ? $invoice->amount($usersCartStatus['total-price-to-pay']) : $invoice->amount(1000);
        $invoice->detail([
            'onlineShopName' => 'perfumeOnlineShop',
            'customerFirstName' => $user->first_name,
            'customerLastName' => $user->last_name,
            'customerId' => $user->id,
        ]);
        $this->bankGatewayUrl = Payment::callbackUrl($this->callbackUrl)->purchase($invoice, function ($driver, $transactionId) use ($usersCartStatus, $user) {
            // store in sold and factorSold models
            $this->soldProductService->storeUsersCartBeforePayment($user, $usersCartStatus, $transactionId);
        })->pay()->getAction();
    }

    public function verify($transactionId, $status)
    {
        if (!$status) {
            throw new ErrorException('payment failed');
        }
        try {
            $receipt = Payment::amount($this->soldProductService->getFactor($transactionId)->total_price_to_pay)->transactionId($transactionId)->verify();
            $this->soldProductService->storeReferenceId($transactionId, $receipt->getReferenceId());
            $this->soldProductService->verifyPayment($transactionId);
            // todo minus from productDB, add to sold column of product db , minus from redis DB, dont verify if its already verified
            $this->reference_id = $receipt->getReferenceId();
        } catch (InvalidPaymentException $exception) {
            info($exception->getMessage());
            throw new \Exception('error'.$exception->getMessage());
        }
    }

    public function getReferenceId()
    {
        return $this->reference_id;
    }
}
