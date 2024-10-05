<?php

namespace App\Http\Resources;

use App\Models\PerfumeBasedFactor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeOfPerfumeBasedFactor extends JsonResource
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
            'name' => $this->resource['name'],
            'price' => $this->resource['price'],
            'volume' => $this->resource['volume'],
            'quantity' => $this->resource['quantity'],
            'warranty' => $this->resource['warranty'],
            'gender' => $this->resource['gender'],
            'isActive' => $this->resource['is_active']?'فعال':'غیر فعال',
            'sold' => $this->resource['sold'],
            'createdAt' => $this->resource['created_at'],
            'discountPercent' => $this->resource['discount_percent'],
            'discountEndTime' => $this->resource['discount_end_date'],
            'discountCard' => $this->resource['discount_card'],
            'perfumeBasedFactor' => $this->resource['perfumeBasedFactor']?PerfumeBasedFactorResource::collection($this->resource['perfumeBasedFactor']):[NULL]
        ];
    }
}
