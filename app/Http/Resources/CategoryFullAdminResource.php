<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryFullAdminResource extends JsonResource
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
            'type' => $this->resource['type'],
            'description' => $this->resource['description'],
            'slug' => $this->resource['slug'],
            'isActive' => empty($this->resource['deleted_at']) ? 'فعال' : 'غیر فعال',
        ];
    }
}
