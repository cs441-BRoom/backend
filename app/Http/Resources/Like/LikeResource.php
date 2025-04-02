<?php

namespace App\Http\Resources\Like;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'like_id' => $this->like_id,
            'news_id' => $this->news_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
