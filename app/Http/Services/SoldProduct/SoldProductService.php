<?php

namespace App\Http\Services\SoldProduct;

use App\Exceptions\ErrorException;
use App\Models\SoldFactor;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class SoldProductService
{
    // todo delete unnecessary data after a while (pending's)
    public function storeUsersCartBeforePayment(User|Authenticatable $user, $usersCartStatus, $transactionId)
    {
        try {
            DB::beginTransaction();
            $factor = SoldFactor::create([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'shipping_price' => $usersCartStatus['shipping-price'],
                'total_price_to_pay' => $usersCartStatus['total-price-to-pay'],
            ]);
            $soldProducts = collect($usersCartStatus['products'])->map(function ($product) {
                return [
                    'product_type' => $product['type'],
                    'product_id' => $product['id'],
                    'quantity' => $product['count'],
                ];
            });
            $factor->soldProducts()->createMany($soldProducts);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            info('error in storing user cart before payment'.$e);
            throw new Exception('error');
        }
    }

    public function getSoldProduct() {}

    public function verifyPayment($transactionId)
    {
        try {
            DB::beginTransaction();
            $sold = SoldFactor::where('transaction_id', $transactionId)->first();
            $sold->update(['status' => 'verified']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            info('error in verifying payment'.$e);
            throw new Exception('error');
        }
    }

    public function failPayment() {}

    public function getSoldProducts(array $query) {}

    public function getFactor($transactionId)
    {
        //todo handle exception message
        $soldFactor = SoldFactor::where('transaction_id', $transactionId)->first();
        if (! $soldFactor) {
            throw new ErrorException('چنین پرداختی انجام نشده است.');
        }
        // todo temporary price
        $soldFactor->total_price_to_pay = 1000;

        return $soldFactor;
    }

    public function storeReferenceId($transactionId, $referenceId)
    {
        try {
            DB::beginTransaction();
            $sold = SoldFactor::where('transaction_id', $transactionId)->first();
            $sold->update(['reference_id' => $referenceId]);
            DB::commit();
        } catch (Exception $e) {
            info('error in storing reference id'.$e);
            throw new Exception('error');
        }
    }
}
