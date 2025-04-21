<?php

namespace App\Http\Actions\Cart;

use App\Exceptions\ErrorException;
use App\Http\Actions\Discount\CalculateDiscount;
use App\Http\Services\Postex\PostexService;
use App\Models\Discount;
use App\Traits\GetProduct;
use App\Traits\HasUserCompletedInfo;
use App\Traits\ReserveProductManagement;
use App\Traits\TotalPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartAction
{
    use GetProduct , HasUserCompletedInfo , ReserveProductManagement , TotalPrice;

    public function handle($user)
    {
        // todo
        // validate cart duplicated value
        $cartProducts = $this->getUserCartProducts($user);
        if (empty($cartProducts)) {
            throw new ErrorException('cart is empty');
        }
        $sessionDiscountCards = Session::get('discount') ?? [];
        $existingProducts = $this->getValidCartProducts($cartProducts); // only available products
        $discountCards = $this->fetchDiscountCards($sessionDiscountCards);

        return $this->calculatePrice($cartProducts, $existingProducts, $discountCards, $user);
    }

    private function calculatePrice(array $cartProducts, Collection $allProductsWithType, Collection $discountCards, $user)
    {
        $output = [];
        foreach ($allProductsWithType as $productType => $products) {
            foreach ($products as $product) {
                $output['products'][$product->id] = [
                    // todo consider other type of products
                    'id' => $product->id,
                    'type' => $productType,
                    'name' => $product->name,
                    'volume' => $product->volume,
                    'slug' => $product->slug,
                    'warranty' => $product->warranty,
                    'count' => $cartProducts[$productType][$product->id],
                    'gender' => $product->gender,
                    'discount_percent' => $this->productHasDiscount($product) ? $product->discount_percent : null,
                    'discount_start_date' => $this->productHasDiscount($product) ? $product->discount_start_date : null,
                    'discount_end_date' => $this->productHasDiscount($product) ? $product->discount_end_date : null,
                    'price' => $product->price,
                    'price-with-discount' => $this->productHasDiscount($product) ? CalculateDiscount::show($product->price, $product->discount_percent) : null,
                    'price-to-pay' => $this->productHasDiscount($product)
                        ? CalculateDiscount::show($product->price, $product->discount_percent) * $cartProducts[$productType][$product->id]
                        : $product['price'] * $cartProducts[$productType][$product->id],
                ];
            }
        }
        $postexService = new PostexService($user);
        $output['price-without-discount'] = $this->calculatePriceWithoutDiscount($output);
        $output['shipping-price'] = $this->hasAddress($user) ? $postexService->calculateShippingPrice($output) : null;
        $output['total-price-to-pay'] = $this->calculateTotalPriceToPay($output, $discountCards);

        return $output;
    }

    private function fetchDiscountCards(mixed $sessionDiscountCards)
    {
        $discountCards = [];
        foreach ($sessionDiscountCards as $sessionDiscountCard) {
            $discountCard = Discount::where([['name', '=', $sessionDiscountCard], ['is_active', '=', true], ['end_date', '>', Carbon::now()]])->first();
            if ($discountCard) {
                $discountCards[] = $discountCard;
            }
        }

        return collect($discountCards);
    }
}
