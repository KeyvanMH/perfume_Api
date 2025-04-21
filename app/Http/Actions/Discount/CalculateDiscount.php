<?php

namespace App\Http\Actions\Discount;

use App\Models\Discount;
use Illuminate\Support\Carbon;

class CalculateDiscount
{
    public static function show($price, $discount): string
    {
        // calculate price for showing to the user
        return $price - ($price * ($discount / 100));
    }

    public function pay($product)
    {
        // calculate price to be paid
    }

    public static function isPerfumesDiscountValid(Carbon $startDate, Carbon $endDate): bool
    {
        if ($startDate > Carbon::now() || $endDate < Carbon::now()) {
            return false;
        }

        return true;
    }

    public static function isDiscountCardValid($discountCard): bool
    {
        if (empty(self::allValidDiscountCard($discountCard))) {
            return false;
        }

        return true;
    }

    private static function allValidDiscountCard($discountCard)
    {
        return Discount::where([['name', '=', $discountCard], ['is_active', '=', true], ['end_date', '>', Carbon::now()]])->first();
    }
}
