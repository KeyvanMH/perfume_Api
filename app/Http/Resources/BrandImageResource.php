<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandImageResource extends JsonResource
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
            //            'path' => $this->resource['image_path'],
            'alt' => $this->resource['alt'],
            'extension' => $this->resource['extension'],
            'size' => $this->resource['size'],
        ];
    }
}
