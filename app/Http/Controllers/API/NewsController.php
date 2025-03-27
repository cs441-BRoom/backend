<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Requests\News\NewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Http\Resources\News\NewsResource;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(NewsRequest $request,int $workspaceId): JsonResponse
    {
//        $request->validated();
        $news = $this->newsService->getAllNewsByWorkspace($workspaceId);
        return response()->json([
            'message' => 'Fetched news successfully.',
            'news' => NewsResource::collection($news),
        ]);
    }

    public function store(CreateNewsRequest $request): JsonResponse
    {
        $news = $this->newsService->createNews($request->validated());
        return response()->json([
            'message' => 'News created successfully.',
            'news' => new NewsResource($news),
        ], 201);
    }

    public function update(UpdateNewsRequest $request, int $newsId): JsonResponse
    {
        $updated = $this->newsService->updateNews($newsId, $request->validated());
        return response()->json([
            'message' => $updated ? 'News updated successfully.' : 'Failed to update news.',
        ]);
    }

    public function destroy(UpdateNewsRequest $request ,int $newId): JsonResponse
    {
        $request->validated();
        $deleted = $this->newsService->deleteNews($newId);
        return response()->json([
            'message' => $deleted ? 'News deleted successfully.' : 'Failed to delete news.',
        ]);
    }
}
