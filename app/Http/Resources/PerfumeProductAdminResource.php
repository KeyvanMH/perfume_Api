<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeProductAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name'],
            'price' => $this->resource['price'],
            'volume' => $this->resource['volume'],
            'quantity' => $this->resource['quantity'],
            'description' => $this->resource['description'],
            'slug' => $this->resource['slug'],
            'gender' => $this->resource['gender'],
            'isActive' => $this->resource['is_active']?' فعال':'غیر فعال ',
            'sold' => $this->resource['sold'],
            'percent' => $this->resource['percent']??NULL,
            'amount' => $this->resource['amount']??NULL,
            'start_date' => $this->resource['start_date']??NULL,
            'end_date' => $this->resource['end_date']??NULL,
            'discount_card' => $this->resource['discount_card']??NULL,
            'discount_card_percent' => $this->resource['discount_card_percent']??NULL,
            'deleted' => !$this->resource['deleted_at']?' فعال':'غیر فعال ',
            'category' => $this->resource['category']['name']??NULL,
            'brand' => $this->resource['brand']['name']??NULL,
        ];
    }
}
