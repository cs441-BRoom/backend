<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CreateCommentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $comment = $this->commentService->createComment([
            'news_id' => $data['news_id'],
            'content' => $data['content'],
        ]);

        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => new CommentResource($comment),
        ], 201);
    }

    public function update(UpdateCommentRequest $request, int $commentId): JsonResponse
    {
        $comment = $this->commentService->updateComment($commentId, $request->validated());

        return response()->json([
            'message' => 'Comment updated successfully.',
            'comment' => new CommentResource($comment),
        ]);
    }

    public function destroy(UpdateCommentRequest $request,int $commentId): JsonResponse
    {
        $request->validated();
        $this->commentService->deleteComment($commentId);

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ], 204);
    }

    public function getCommentsByNewsId(int $newsId): JsonResponse
    {
        $comments = $this->commentService->getCommentsByNewsId($newsId);

        return response()->json([
            'message' => 'Fetched comments successfully.',
            'comments' => CommentResource::collection($comments),
        ]);
    }
}
