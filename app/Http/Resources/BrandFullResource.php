<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'logo_path' => $this->resource['logo_path'],
            'link' => $this->resource['link'],
            'description' => $this->resource['description'],
            'title' => $this->resource['title'],
            'slug' => $this->resource['slug'],
            'images' => BrandImageResource::collection($this->resource['image']),
        ];
    }
}
