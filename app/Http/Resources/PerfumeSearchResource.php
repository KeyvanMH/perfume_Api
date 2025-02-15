<?php

namespace App\Http\Resources;

use App\Http\Action\Discount\CalculateDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name']??NULL,
            'price' => $this->resource['price']??NULL,
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'],$this->resource['discount_percent']),
            'volume' => $this->resource['volume']??NULL,
            'quantity' => $this->resource['is_active']?$this->resource['quantity']-$this->resource['reserve']:0,
            'warranty' => $this->resource['warranty']??NULL,
            'gender' => $this->resource['gender']??NULL,
            'discount_percent' => $this->resource['discount_percent']??NULL,
            'slug' => $this->resource['slug']??NULL,
            'category' => $this->resource['category']['name']??NULL,
            'brand' => $this->resource['brand']['name']??NULL,
            'images' => $this->when(isset($this->resource['images']),ProductImageResource::collection($this->resource['images']))
        ];
    }
}
