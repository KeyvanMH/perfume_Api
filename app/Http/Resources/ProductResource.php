<?php

namespace App\Http\Resources;

use App\Http\Actions\Discount\CalculateDiscount;
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
        // todo add other attribute of other type of products
        return [
            'id' => $this->resource['id'],
            'type' => $this->resource['type'],
            'name' => $this->resource['name'] ?? null,
            'price' => $this->resource['price'] ?? null,
            'quantity' => $this->resource['is_active'] ? $this->resource['quantity'] : 0,

            // for perfumes
            'volume' => $this->resource['volume'] ?? null,
            'warranty' => $this->resource['warranty'] ?? null,
            'description' => $this->resource['description'] ?? null,
            'gender' => $this->resource['gender'] ?? null,
            // for clothes

            // for watches

            // for cosmetic

            'percent' => $this->resource['discount_percent'],
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'], $this->resource['discount_percent']),
            'amount' => $this->resource['amount'] ?? null,
            'discountEndTime' => $this->resource['end_date'] ?? null,
            'slug' => $this->resource['slug'] ?? null,
            'category' => $this->resource['category']['name'] ?? null,
            'brand' => $this->resource['brand']['name'] ?? null,
        ];
    }
}
