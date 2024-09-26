<?php

namespace App\Http\Resources;

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
            'name' => $this->resource['name']??NULL,
            'price' => $this->resource['price']??NULL,
            'volume' => $this->resource['volume']??NULL,
            //TODO change quantity to zero of the is_active is false
            'quantity' => $this->resource['quantity']??NULL,
            'warranty' => $this->resource['warranty']??NULL,
            'description' => $this->resource['description']??NULL,
            'gender' => $this->resource['gender']??NULL,
            'percent' => $this->resource['percent']??NULL,
            'amount' => $this->resource['amount']??NULL,
            'discountEndTime' => $this->resource['end_date']??NULL,
            'slug' => $this->resource['slug']??NULL,
            'category' => $this->resource['category']['name']??NULL,
            'brand' => $this->resource['brand']['name']??NULL,
        ];
    }
}
