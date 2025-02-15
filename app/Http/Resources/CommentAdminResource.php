<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentAdminResource extends JsonResource
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
            'comment' => $this->resource['comment'],
            'user' => $this->when(isset($this->resource['user']),new UserForAdminResource($this->resource['user']),null),
            'replies' => $this->when(isset($this->resource['replies']),new CommentReplyAdminResource($this->resource['replies']),null),
            'isActive' => $this->resource['deleted_at']?'پاک شده':'فعال',
        ];
    }
}
