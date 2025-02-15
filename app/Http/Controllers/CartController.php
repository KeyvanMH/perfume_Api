<?php

namespace App\Http\Controllers;

use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreCartRequest;
use App\Models\Perfume;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    /**
     * Display a listing of the cart.
     */
    public function index()
    {
        return Session::get('products')??[];
    }


    /**
     * Store a newly created resource in cart.
     */
    public function store(StoreCartRequest $request)
    {
        $productId = $request->validated('product_id');
        $perfume = Perfume::find($productId);
        //todo add to reserve product table
//        $productCountInCart = (Session::get($productId)??0)+1;
        // check for any error in input or session
        if(!$perfume || !$this->canBeSold($perfume) || !$this->validateCartInput()){
            return response()->json(['message' => DefaultConst::INVALID_INPUT ],400);
        }
        $productCountInCart = $this->countCartProduct($productId);
        $array = $this->addProductToArray($perfume->id,$productCountInCart);
        $this->storeInSession($array);
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }


    /**
     * Remove the specified resource from cart.
     */
    public function destroy(string $id)
    {
        if(!Session::has('products') || !is_array(Session::get('products')) || !$this->destroySession($id)){
            return response()->json(['message' => DefaultConst::INVALID_INPUT]);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);

    }
    public function destroyAll(){
        Session::flush();
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }



    private function validateCartInput():bool {
        $products = Session::get('products');
        if((is_array($products) && count($products) > DefaultConst::MAX_CART_LIMIT)){
            return false;
        }
        return true;
    }
    private function canBeSold($perfume):bool{
        if(!$perfume->is_active || $perfume->quantity == 0){
            return false;
        }
        return true;
    }
    private function storeInSession($value , $sessionName = 'products'):void{
        Session::put($sessionName,$value);
    }

    private function destroySession($productId):bool {
        if(!$this->isSessionAvailable($productId)){
            return false;
        }
        $products = Session::pull('products');
        unset($products[$productId]);
        Session::put($products);
        return true;
    }

    private function isSessionAvailable($productId):bool {
        try{
            $products = Session::get('products');
            if(!is_array($products)){
                Session::flush();
                throw new \InvalidArgumentException("expected array for products in shopping cart , changed manually by the user");
            }
            if (array_key_exists($productId, $products)) {
                return true;
            }
            return false;
        }catch (\Exception $e){
            info('session manipulation error'.$e->getMessage());
            http_response_code(404);
            die();
        }
    }

    private function countCartProduct(mixed $productId): int {
        if(!is_array(Session::get('products'))){
            return 1;
        }
        foreach(Session::get('products') as $id => $quantity){
            if($id == $productId){
                return (integer)$quantity + 1;
            }
        }
        return 1;
    }

    private function addProductToArray($productId,$count): array {
        $products = Session::pull('products')??[];
        try{
            if(!is_array($products)){
                // all we are is talking over each other "emily armstrong" :)))
                Session::flush();
                throw new \InvalidArgumentException("expected array for products in shopping cart , changed manually by the user");
            }
            foreach ($products as $id => $quantity){
                if($id == $productId){
                    $products[$id] = $count;
                    return $products;
                }
            }
            $products[$productId] = $count;
            return $products;
        }catch(\Exception $e){
            info('session manipulation error'.$e->getMessage());
            http_response_code(404);
            die('error');
        }
    }

}
