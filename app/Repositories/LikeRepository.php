<?php

namespace App\Repositories;

use App\Models\Like;
use App\Repositories\Traits\SimpleCRUD;

class LikeRepository
{
    use SimpleCRUD;

    protected string|Like $model = Like::class;

    public function create(array $data)
    {
        return Like::create($data);
    }

    public function findByNewsAndUser(int $newsId, int $userId)
    {
        return Like::where('news_id', $newsId)
            ->where('user_id', $userId)
            ->first();
    }

}
