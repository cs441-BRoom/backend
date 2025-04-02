<?php

namespace App\Http\Resources\News;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isLikedByUser = false;
        if (auth()->check()) {
            $isLikedByUser = Like::where('news_id', $this->news_id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return [
            'news_id' => $this->news_id,
            'workspace_id' => $this->workspace_id,
            'title' => $this->title,
            'content' => $this->content,
            'comments_count' => $this->comments_count,
            'like_count' => $this->likes()->count(),
            'is_liked_by_user' => $isLikedByUser,
            'files' => $this->files ?? [],
            'created_by' => $this->user ? "{$this->user->firstname} {$this->user->lastname}" : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
