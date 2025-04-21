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
            'id' => $this->resource['id'],
            'name' => $this->resource['name'] ?? null,
            'price' => $this->resource['price'] ?? null,
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'], $this->resource['discount_percent']),
            'volume' => $this->resource['volume'] ?? null,
            'quantity' => $this->resource['is_active'] ? $this->resource['quantity'] - $this->resource['reserve'] : 0,
            'warranty' => $this->resource['warranty'] ?? null,
            'gender' => $this->resource['gender'] ?? null,
            'discount_percent' => $this->resource['discount_percent'] ?? null,
            'slug' => $this->resource['slug'] ?? null,
            'category' => $this->resource['category']['name'] ?? null,
            'brand' => $this->resource['brand']['name'] ?? null,
            //            'images' => $this->when(isset($this->resource['images']),ProductImageResource::collection($this->resource['images']))
        ];
    }
}
