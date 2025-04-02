<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\DislikeRequest;
use App\Http\Requests\Like\LikeRequest;
use App\Http\Resources\Like\LikeResource;
use App\Services\LikeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    protected LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function like(LikeRequest $request): JsonResponse
    {
        $request->validated();
        $userId = auth()->id();
        $newsId = $request->input('news_id');

        try {
            $like = $this->likeService->likeNews($newsId, $userId);
            return response()->json([
                'message' => 'News liked successfully.',
                'like' => new LikeResource($like),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function unlike(DislikeRequest $request, $likeId): JsonResponse
    {
        $request->validated();
        try {
            $this->likeService->unlikeNews($likeId);
            return response()->json([
                'message' => 'Like removed successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Like not found.',
            ], 404);
        }
    }
}
