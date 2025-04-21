<?php

namespace App\Traits;

use App\Exceptions\ErrorException;
use App\Http\Const\DefaultConst;
use App\Models\Perfume;

trait GetProduct
{
    private function getProductByType($inputProductId, $inputProductType)
    {
        foreach (DefaultConst::PRODUCT_TYPE as $productType) {
            if ($inputProductType == $productType) {
                switch ($productType) {
                    case 'perfume':
                        return Perfume::where('id', '=', $inputProductId)->with(['brand', 'category'])->first();
                    case 'cloth':
                        return Cloth::where('id', '=', $inputProductId)->with(['brand', 'category'])->first();
                    case 'watch':
                        return Watch::where('id', '=', $inputProductId)->with(['brand', 'category'])->first();
                    default:
                        throw new ErrorException(DefaultConst::INVALID_PRODUCT_TYPE);
                }
            }
        }
    }

    private function getValidCartProducts(mixed $allCartProductsType)
    {
        $products = [];
        foreach ($allCartProductsType as $productType => $products) {
            foreach ($products as $productId => $count) {
                switch ($productType) {
                    case 'perfume':
                        $product = Perfume::where('id', $productId)->first();
                        break;
                    case 'cloth':
                        $product = Cloth::where('id', $productId)->first();
                        break;
                    case 'watch':
                        $product = Watch::where('id', $productId)->first();
                        break;
                    default:
                        throw new ErrorException(DefaultConst::INVALID_PRODUCT_TYPE);
                }
                if ($product) {
                    $output[$productType][] = $product;
                }
            }
        }

        return collect($output);
    }
}
