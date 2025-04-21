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
            'name' => $this->resource['name'] ?? null,
            'price' => $this->resource['price'] ?? null,
            'volume' => $this->resource['volume'] ?? null,
            'quantity' => $this->resource['is_active'] ? $this->resource['quantity'] : 0,
            'warranty' => $this->resource['warranty'] ?? null,
            'description' => $this->resource['description'] ?? null,
            'gender' => $this->resource['gender'] ?? null,
            'percent' => $this->resource['discount_percent'],
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'], $this->resource['discount_percent']),
            'amount' => $this->resource['amount'] ?? null,
            'discountEndTime' => $this->resource['end_date'] ?? null,
            'slug' => $this->resource['slug'] ?? null,
            'category' => $this->resource['category']['name'] ?? null,
            'brand' => $this->resource['brand']['name'] ?? null,
            'images' => $this->when(isset($this->resource['images']), ProductImageResource::collection($this->resource['images'])),
        ];
    }
}
