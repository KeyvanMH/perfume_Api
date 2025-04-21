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
            'reservedQuantity' => $this->resource['reserved'],
            'description' => $this->resource['description'],
            'slug' => $this->resource['slug'],
            'gender' => $this->resource['gender'],
            'isActive' => $this->resource['is_active'] ? ' فعال' : 'غیر فعال ',
            'sold' => $this->resource['sold'],
            'percent' => $this->resource['percent'] ?? null,
            'amount' => $this->resource['amount'] ?? null,
            'start_date' => $this->resource['start_date'] ?? null,
            'end_date' => $this->resource['end_date'] ?? null,
            'discount_card' => $this->resource['discount_card'] ?? null,
            'discount_card_percent' => $this->resource['discount_card_percent'] ?? null,
            'deleted' => ! $this->resource['deleted_at'] ? ' فعال' : 'غیر فعال ',
            'category' => $this->resource['category']['name'] ?? null,
            'brand' => $this->resource['brand']['name'] ?? null,
        ];
    }
}
