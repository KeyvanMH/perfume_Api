<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactorResource extends JsonResource
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
            'isActive' => $this->resource['deleted_at']?'غیر فعال':'فعال',
            'createdAt' => $this->resource['created_at'],
            'updatedAt' => $this->resource['updated_at'],
            'userId' => $this->resource['user']['id'],
            'userName' => $this->resource['user']['last_name'],
            'userRole' => $this->resource['user']['role'],
            'perfumesOfFactor' =>  PerfumeBasedFactorResource::collection($this->resource['perfumeBasedFactor']),
        ];
    }
}
