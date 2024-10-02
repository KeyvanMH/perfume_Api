<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserForAdminResource extends JsonResource
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
            'phoneNumber' => $this->resource['phone_number'],
            'firstName' => $this->resource['first_name'],
            'lastName' => $this->resource['last_name'],
            'email' => $this->resource['email'],
            'postNumber' => $this->resource['post_number'],
            'isEmailVerified' => $this->resource['email_verified_at']?'فعال':'غیر فعال',
            'role' => $this->resource['role'],
            'createdAt' => $this->resource['post_number'],

        ];
    }
}
