<?php

namespace App\Http\Controllers;

use App\Http\Action\Discount\CalculateDiscount;
use App\Http\Const\DefaultConst;
use App\Http\Requests\StoreApplyDiscountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApplyDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Session::get('discount');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplyDiscountRequest $request)
    {
        //one shopping may have multiple discount cards
        $discount_card = $request->validated('discount_card_name');
        $discountInSession = Session::get('discount')??[];

        if(!CalculateDiscount::isDiscountCardValid($discount_card) || in_array($discount_card,$discountInSession) || !is_array($discountInSession)){
            return response()->json(['message' => DefaultConst::NOT_VALID]);
        }
        $this->saveDiscountCardToSession($discount_card);
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $inputDiscountCard)
    {
        $discountArray = Session::pull('discount')??[];
        $this->destroyDiscountCard($discountArray,$inputDiscountCard);
        if(!empty($discountArray)){
            $this->bulkSaveDiscountCardToSession($discountArray);
        }
        return response()->json(['message' => DefaultConst::SUCCESSFUL]);
    }

    private function saveDiscountCardToSession($discount_card): void {
        if(!Session::has('discount')){
            Session::put('discount',[$discount_card]);
            return;
        }
        Session::push('discount',$discount_card);
    }

    private function destroyDiscountCard(&$discountArray,$inputDiscountCard): void {
        foreach ($discountArray as $key => $discountCard){
            if ($discountCard == $inputDiscountCard){
                unset($discountArray[$key]);
                break;
            }
        }
    }

    private function bulkSaveDiscountCardToSession($discountArray): void {
        Session::put('discount',array_values($discountArray));
    }
}
