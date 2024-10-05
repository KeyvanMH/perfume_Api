<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandAdminResource extends JsonResource
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
            'logo_path' => $this->resource['logo_path'],
            'link' => $this->resource['link'],
            'description' => $this->resource['description'],
            'title' => $this->resource['title'],
            'slug' => $this->resource['slug'],
            'status' => !$this->resource['deleted_at']?'فعال':' حذف شده',
        ];
    }
}
