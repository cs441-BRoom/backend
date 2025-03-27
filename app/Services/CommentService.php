<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{
    protected CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(array $data)
    {
        $user = auth()->user();

        return $this->commentRepository->create([
            'news_id' => $data['news_id'],
            'content' => $data['content'],
            'created_by' => $user->user_id,
        ]);
    }

    public function updateComment(int $commentId, array $data): Comment
    {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            throw new ModelNotFoundException("Comment not found.");
        }

        return $this->commentRepository->update($comment, $data);
    }

    public function deleteComment(int $commentId): void
    {
        $comment = $this->commentRepository->findById($commentId);

        if (!$comment) {
            throw new ModelNotFoundException("Comment not found.");
        }

        $this->commentRepository->delete($comment->comment_id);
    }

    public function getCommentsByNewsId(int $newsId)
    {
        return $this->commentRepository->findByNewsId($newsId);
    }
}
