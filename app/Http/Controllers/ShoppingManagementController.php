<?php

namespace App\Http\Controllers;

use App\Http\Action\Discount\CalculateDiscount;
use App\Http\Action\Postex\PostexService;
use App\Http\Const\DefaultConst;
use App\Http\Resources\ShoppingManagementResource;
use App\Models\Discount;
use App\Models\Perfume;
use App\Models\User;
use App\Traits\TotalPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Traits\UserHasAddress;
class ShoppingManagementController extends Controller
{
    use UserHasAddress , TotalPrice;
    /**
     * Display status of buying journey
     */
    public function index()
    {
        //todo
        // validate cart existence and duplicated value
        // validate if the user has completed the info,
        // get session and calculate discount api call to the post transportation
        // see if the perfuems in the cart are still avaiable
        // return price and post price and product detail and discount card detail
        $cartProducts = Session::get('products')??[];
        if(empty($cartProducts)){
            return response()->json(['message' => DefaultConst::NOT_FOUND],400);
        }
        $sessionDiscountCards = Session::get('discount')??[];
        $user = auth()->user();
        $existingProduct = $this->fetchCartProducts($cartProducts);//only available products
        $discountCards = $this->fetchDiscountCards($sessionDiscountCards);
//        return ShoppingManagementResource::collection($this->calculateTotalPrice($cartProducts,$existingProduct,$discountCards,$user));
        return $this->calculateTotalPrice($cartProducts,$existingProduct,$discountCards,$user);
    }

    private function calculateTotalPrice(array $cartProducts,Collection $products, Collection $discountCards,  $user) {
        $output = [];
        foreach ($products as $product){
            $output['products'][$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'volume' =>  $product->volume,
                'slug' => $product->slug,
                'warranty' => $product->warranty,
                'count' => $cartProducts[$product->id],
                'gender' => $product->gender,
                'discount_percent' => $this->perfumeHasDiscount($product)?$product->discount_percent:null,
                'discount_start_date' => $this->perfumeHasDiscount($product)?$product->discount_start_date:null,
                'discount_end_date' => $this->perfumeHasDiscount($product)?$product->discount_end_date:null,
                'price' => $product->price,
                'price-with-discount' => $this->perfumeHasDiscount($product)?CalculateDiscount::show($product->price,$product->discount_percent):null,
                'price-to-pay' => $this->perfumeHasDiscount($product)
                    ?CalculateDiscount::show($product->price,$product->discount_percent)*$cartProducts[$product->id]
                    :$product['price']*$cartProducts[$product->id],
            ];
        }
        $postexService = new PostexService($user);
        $output['price-without-discount'] = $this->calculatePriceWithoutDiscount($output);
        $output['shipping-price'] = isset($user->city_id)?$postexService->calculateShippingPrice($output):null;
        $output['total-price-to-pay'] = $this->calculateTotalPriceToPay($output,$discountCards);
        return $output;
    }

    private function fetchCartProducts(mixed $cartProducts) {
        $products = [];
        foreach($cartProducts as $cartProduct => $count){
            $product = Perfume::where('id',$cartProduct)->first();
            if($product){
                $products[] = $product;
            }
        }
        return collect($products);
    }

    private function fetchDiscountCards(mixed $sessionDiscountCards) {
        $discountCards = [];
        foreach($sessionDiscountCards as $sessionDiscountCard){
            $discountCard = Discount::where([['name','=',$sessionDiscountCard],['is_active','=',true],['end_date','>',Carbon::now()]])->first();
            if($discountCard){
                $discountCards[] = $discountCard;
            }
        }
        return collect($discountCards);
    }


}
