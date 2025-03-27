<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Traits\SimpleCRUD;

class CommentRepository
{
    use SimpleCRUD;

    protected string|Comment $model = Comment::class;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Comment
    {
        return $this->model->create($data);
    }

    public function findById(int $commentId): ?Comment
    {
        return $this->model->find($commentId);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function findByNewsId(int $newsId)
    {
        return $this->model->where('news_id', $newsId)->with('user')->get();
    }

}
