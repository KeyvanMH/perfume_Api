<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminFaqResource extends JsonResource
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
            'question' => $this->resource['question'],
            'answer' => $this->resource['answer'],
            'is_active' => ! $this->resource['deleted_at'] ? 'فعال' : 'غیر فعال',
        ];
    }
}
