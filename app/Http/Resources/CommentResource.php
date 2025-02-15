<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
//            'user_id' => $this->resource['user_id'],
//            'perfume_id' => $this->resource['perfume_id'],
            'comment' => $this->resource['comment'],
//            'deleted_at' => $this->resource['deleted_at'],
//            'created_at' => $this->resource['created_at'],
//            'updated_at' => $this->resource['updated_at'],
            'user' => $this->resource['user']?(new UserResource($this->resource['user'])):null,
            'replies' => $this->resource['replies']?CommentRepliesResource::collection($this->resource['replies']):null,
        ];
    }
}
