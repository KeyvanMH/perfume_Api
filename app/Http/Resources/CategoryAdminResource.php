<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryAdminResource extends JsonResource
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
            'type' => $this->resource['type'],
            'slug' => $this->resource['slug'],
            'isActive' => empty($this->resource['deleted_at']) ? 'فعال' : 'غیر فعال',
        ];
    }
}
