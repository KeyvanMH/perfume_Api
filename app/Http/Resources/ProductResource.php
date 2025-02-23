<?php

namespace App\Http\Resources;

use App\Http\Action\Discount\CalculateDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //todo add other attribute of other type of products
        return [
            'id' => $this->resource['id'],
            'type' => $this->resource['type'],
            'name' => $this->resource['name']??NULL,
            'price' => $this->resource['price']??NULL,
            'quantity' => $this->resource['is_active']?$this->resource['quantity']:0,

            //for perfumes
            'volume' => $this->resource['volume']??NULL,
            'warranty' => $this->resource['warranty']??NULL,
            'description' => $this->resource['description']??NULL,
            'gender' => $this->resource['gender']??NULL,
            //for clothes

            //for watches

            //for cosmetic


            'percent' => $this->resource['discount_percent'],
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'],$this->resource['discount_percent']),
            'amount' => $this->resource['amount']??NULL,
            'discountEndTime' => $this->resource['end_date']??NULL,
            'slug' => $this->resource['slug']??NULL,
            'category' => $this->resource['category']['name']??NULL,
            'brand' => $this->resource['brand']['name']??NULL,
        ];
    }
}
