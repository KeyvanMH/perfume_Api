<?php

namespace App\Http\Resources;

use App\Http\Action\Discount\CalculateDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'perfume',
            'id' => $this->resource['id'],
            'name' => $this->resource['name']??NULL,
            'price' => $this->resource['price']??NULL,
            'volume' => $this->resource['volume']??NULL,
            'quantity' => $this->resource['is_active']?$this->resource['quantity']:0,
            'warranty' => $this->resource['warranty']??NULL,
            'description' => $this->resource['description']??NULL,
            'gender' => $this->resource['gender']??NULL,
            'percent' => $this->resource['discount_percent'],
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'],$this->resource['discount_percent']),
            'amount' => $this->resource['amount']??NULL,
            'discountEndTime' => $this->resource['end_date']??NULL,
            'slug' => $this->resource['slug']??NULL,
            'category' => $this->resource['category']['name']??NULL,
            'brand' => $this->resource['brand']['name']??NULL,
            'images' => $this->when(isset($this->resource['images']),ProductImageResource::collection($this->resource['images']))
        ];
    }
}
