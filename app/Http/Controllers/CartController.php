<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\DeleteCartRequest;
use App\Http\Requests\StoreCartRequest;
use App\Http\Resources\ProductResource;
use App\Models\Perfume;
use App\Traits\ReserveProductManagement;
use App\Traits\GetProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use App\Exceptions\ErrorException;

class CartController extends Controller
{
    use GetProduct,ReserveProductManagement;

    /**
     * Display a listing of the cart.
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $products = [];
        foreach(DefaultConst::PRODUCT_TYPE as $productType) {
            $cart = Redis::hgetall("cart:product_type=$productType&user_id=$userId");
            if(empty($cart)){
                continue;
            }
            foreach ($cart as $productId => $quantity) {
                $product = $this->getProductByType($productId, $productType);
                $products[] = $product;
            }
        }
        return ProductResource::collection($products);
    }


    /**
     * Store a newly created resource in cart.
     */
    public function store(StoreCartRequest $request)
    {
        $userId = auth()->user()->id;
        [   'product_id' => $inputProductId,
            'product_quantity' => $inputProductQuantity,
            'product_type' => $inputProductType
        ] = $request->validated();
//        info($inputProductId);
        $product = $this->getProduct($inputProductId,$inputProductType);
//        info($product);
        // check for any error in input or reserved product
        $this->validateStoreCart($product,$inputProductQuantity,$inputProductType);
        $this->StoreCart($product,$inputProductQuantity,$inputProductType,$userId);
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }


    /**
     * Remove the specified resource from cart.
     */
    public function destroy(DeleteCartRequest $request)
    {
        //todo changed doc
        $userId = auth()->user()->id;
        [   'product_id' => $inputProductId,
            'product_type' => $inputProductType,
            'product_quantity' => $inputProductQuantity
        ] = $request->validated();
        $this->deleteCart($inputProductId,$inputProductType,$inputProductQuantity,$userId);
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }


    public function destroyAll(){
        $userId = auth()->user()->id;
        foreach (DefaultConst::PRODUCT_TYPE as $productType) {
            Redis::del("cart:product_type=$productType&user_id=$userId");
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    private function getProduct($inputProductId, $inputProductType) {
        foreach(DefaultConst::PRODUCT_TYPE as $productType){
            if($inputProductType == $productType){
                return $this->getProductByType($inputProductId,$productType);
            }
        }
    }

    private function validateStoreCart($product, $inputProductQuantity , $inputProductType) {
        $reservedProducts = $this->getReservedProduct($product->id,$inputProductType);
        // we send error if: product not found or quantity minus reserved is not enough or product is not active
        if(!$product || ($product->quantity - $reservedProducts) < $inputProductQuantity || !$product->is_active){
            throw new ErrorException(DefaultConst::INVALID_INPUT);
        }

    }

    private function StoreCart($product, $inputProductQuantity,$inputProductType,$userId) {
        Redis::hincrby("cart:product_type=$inputProductType&user_id=$userId", $product->id, $inputProductQuantity);
        Redis::expire("cart:product_type=watch&user_id=4", 30 * 60);
    }

    private function deleteCart($inputProductId, $inputProductType, $inputProductQuantity, $userId) {
        if(!Redis::exists("cart:product_type=$inputProductType&user_id=$userId") ){
            return;
        }
        $cartQuantity = Redis::hget("cart:product_type=$inputProductType&user_id=$userId", $inputProductId);
        if($cartQuantity-$inputProductQuantity < 0){
            Redis::hdel("cart:product_type=$inputProductType&user_id=$userId", $inputProductId);
            return;
        }
        Redis::hincrby("cart:product_type=$inputProductType&user_id=$userId", $inputProductId, -$inputProductQuantity);
    }


}
