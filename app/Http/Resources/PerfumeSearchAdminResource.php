<?php

namespace App\Http\Resources;

use App\Http\Action\Discount\CalculateDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeSearchAdminResource extends JsonResource
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
            'priceWithDiscount' => CalculateDiscount::show($this->resource['price'], $this->resource['discount_percent']),
            'volume' => $this->resource['volume'],
            'quantity' => $this->resource['quantity'],
            'slug' => $this->resource['slug'],
            'gender' => $this->resource['gender'],
            'isActive' => $this->resource['is_active'] ? ' فعال' : 'غیر فعال ',
            'sold' => $this->resource['sold'],
            'percent' => $this->resource['percent'],
            'amount' => $this->resource['amount'],
            'category' => $this->resource['category']['name'] ?? null,
            'brand' => $this->resource['brand']['name'] ?? null,
        ];
    }
}
