<?php
namespace App\Traits;
use App\Exceptions\InvalidMimeTypeException;
use App\Http\Action\Discount\CalculateDiscount;
use App\Models\Discount;
use Illuminate\Support\Collection;

trait TotalPrice {
    protected function calculateTotalPriceToPay($output,Collection $discountCards) {
        $perfumeDiscountCards = [];
        $cartDiscountCards = [];
        foreach ($discountCards as $discountCard){
            if($discountCard->perfume_id == null){
                $cartDiscountCards[] = $discountCard;
            }else{
                $perfumeDiscountCards[] = $discountCard;
            }
        }
        $price = $this->calculatePriceWithoutDiscount($output);
        $perfumeDiscount = $this->calculatePerfumeDiscount($output);
        $perfumeDiscountCard = $this->calculatePerfumeDiscountCard($output,$perfumeDiscountCards);
        $cartDiscountCard = $this->calculateCartDiscountCard($output,$price,$cartDiscountCards);
        $totalPriceToPay = $price - $perfumeDiscount - $perfumeDiscountCard - $cartDiscountCard;
        return $totalPriceToPay;
    }

    protected function calculatePriceWithoutDiscount(array $output){
        $price = 0;
        foreach($output['products'] as $product){
            $price += $product['price']*$product['count'];
        }
        return $price;
    }

    private function perfumeHasDiscount($product) {
        if(
            $product['discount_percent'] &&
            $product['discount_start_date'] &&
            $product['discount_end_date'] &&
            CalculateDiscount::isPerfumesDiscountValid($product['discount_start_date'],$product['discount_end_date'])
        ){
            return true;
        }
        return false;
    }


    private function calculatePerfumeDiscount($output) {
        $discount = 0;
        foreach ($output['products'] as $product){
            if($this->perfumeHasDiscount($product)){
                $discount += ($product['price']*$product['count']) - (CalculateDiscount::show($product['price'],$product['discount_percent'])*$product['count']);
            }
        }
        return $discount;
    }

    private function calculatePerfumeDiscountCard($output,$perfumeDiscountCards) {
        $discount = 0;
        foreach ($output['products'] as $product){
            foreach ($perfumeDiscountCards as $discountCard){
                if($discountCard->perfume_id == $product['id']){
                    $discount += ($product['price'] * $product['count'])- (CalculateDiscount::show($product['price'],$discountCard->discount_percent)*$product['count']);
                }
            }
        }
        return $discount;
    }


    private function calculateCartDiscountCard($output, int $price, array $cartDiscountCards) {
        $discount = 0;
        foreach ($cartDiscountCards as $discountCard){
                $discount += $price - (CalculateDiscount::show($price,$discountCard->percent));
        }
        return $discount;
    }
}
