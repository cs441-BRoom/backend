<?php

namespace App\Services;

use App\Models\Like;
use App\Repositories\LikeRepository;

class LikeService
{
    private LikeRepository $likeRepository;

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function likeNews(int $newsId, int $userId)
    {
        $existingLike = $this->likeRepository->findByNewsAndUser($newsId, $userId);

        if ($existingLike) {
            throw new \Exception('You already liked this news.');
        }

        return $this->likeRepository->create([
            'news_id' => $newsId,
            'user_id' => $userId,
        ]);
    }

    public function unlikeNews(int $newsId)
    {
        $userId = auth()->id();
        $like = Like::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();

        if (!$like) {
            throw new \Exception('You have not liked this news.');
        }

        $like->forceDelete();

        return $like;
    }


}
