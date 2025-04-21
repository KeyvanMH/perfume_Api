<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminContactUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'phoneNumber' => $this->resource['phone_number'],
            'description' => $this->resource['description'],
            'is_active' => ! $this->resource['deleted_at'] ? 'فعال' : 'غیر فعال',

        ];
        // Only add email if it's not null
        if ($this->resource['email'] !== null) {
            $data['email'] = $this->resource['email'];
        }

        return $data;
    }
}
