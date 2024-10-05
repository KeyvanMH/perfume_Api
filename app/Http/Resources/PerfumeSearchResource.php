<?php

namespace App\Http\Resources;

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
            'volume' => $this->resource['volume']??NULL,
            //TODO change quantity if is_active is false
            'quantity' => $this->resource['quantity']??NULL,
            'warranty' => $this->resource['warranty']??NULL,
            'gender' => $this->resource['gender']??NULL,
            'percent' => $this->resource['discount_percent']??NULL,
            'slug' => $this->resource['slug']??NULL,
            'category' => $this->resource['category']['name']??NULL,
            'brand' => $this->resource['brand']['name']??NULL,
        ];
    }
}
