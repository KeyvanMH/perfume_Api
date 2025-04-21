<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountAdminResource extends JsonResource
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
            'startDate' => $this->resource['start_date'],
            'percent' => $this->resource['percent'],
            'endDate' => $this->resource['end_date'],
            'isActive' => $this->resource['status'] == 'active' ? 'فعال' : 'غیر فعال',
            'isDeleted' => ! $this->resource['deleted_at'] ? 'فعال' : 'غیر فعال',
            'product' => $this->when(! empty($this->resource['product']), new PerfumeProductAdminResource($this->resource['product'])),
        ];

    }
}
