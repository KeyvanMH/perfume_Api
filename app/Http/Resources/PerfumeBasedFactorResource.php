<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeBasedFactorResource extends JsonResource
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
            'volume' => $this->resource['volume'],
            'price' => $this->resource['price'],
            'stock' => $this->resource['stock'],
            'sold' => $this->resource['sold'],
            'isActive' => $this->resource['is_active'] ? 'در صف فروش' : 'فروش متوقف شده است',
            'warranty' => $this->resource['warranty'],
            'gender' => $this->resource['gender'],
            'deleted' => $this->resource['deleted_at'] ? 'پاک شده است' : 'فعال',
            'updatedAt' => $this->resource['updated_at'],
        ];
    }
}
