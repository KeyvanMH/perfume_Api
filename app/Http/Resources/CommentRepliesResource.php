<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentRepliesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->resource['id'],
            //            'perfume_comment_id' => $this->resource['perfume_comment_id'],
            //            'user_id' => $this->resource['user_id'],
            'user' => $this->resource['user'] ? (new UserResource($this->resource['user'])) : null,
            'reply' => $this->resource['reply'],
            //            'deleted_at' => $this->resource['deleted_at'],
            //            'created_at' => $this->resource['created_at'],
            //            'updated_at' => $this->resource['updated_at'],
        ];
    }
}
