<?php

namespace App\Traits;

use App\Exceptions\ErrorException;
use App\Http\Const\DefaultConst;
use App\Models\Perfume;
use Exception;

trait GetProduct
{
    private function getProductByType($inputProductId, $inputProductType) {
        foreach(DefaultConst::PRODUCT_TYPE as $productType){
            if($inputProductType == $productType){
                switch ($productType) {
                    case 'perfume':
                        return Perfume::where('id','=',$inputProductId)->with(['brand','category'])->first();
                    case 'cloth':
                        return Cloth::where('id','=',$inputProductId)->with(['brand','category'])->first();
                    case 'watch':
                        return Watch::where('id','=',$inputProductId)->with(['brand','category'])->first();
                    default:
                        throw new ErrorException(DefaultConst::INVALID_PRODUCT_TYPE);
                }
            }
        }
    }
}
