<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\CreateNewsRequest;
use App\Http\Requests\News\NewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Http\Resources\News\NewsResource;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use App\Repositories\NewsRepository;

class NewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService, NewsRepository $newsRepository)
    {
        $this->newsService = $newsService;
        $this->newsRepository = $newsRepository;
    }

    public function index(NewsRequest $request,int $workspaceId): JsonResponse
    {
        $request->validated();
        $news = $this->newsService->getAllNewsByWorkspace($workspaceId);
        return response()->json([
            'message' => 'Fetched news successfully.',
            'news' => NewsResource::collection($news),
        ]);
    }

    public function store(CreateNewsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $news = $this->newsService->createNews($validated);

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $index => $file) {
                $file->storeAs('workspaces/' . $validated['workspace_id'] . '/news/' . $news->news_id, $index . '.' . $file->getClientOriginalExtension());
            }
        }

        return response()->json([
            'message' => 'News created successfully.',
            'news' => new NewsResource($news),
        ], 201);
    }

    public function update(UpdateNewsRequest $request, int $newsId): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('files')) {
            $news = $this->newsRepository->getById($newsId);
            $workspace_id = $news->workspace->workspace_id;

            // Delete old images
            $files = Storage::disk('s3')->files('workspaces/' . $workspace_id . '/news/' . $newsId);
            Storage::disk('s3')->delete($files);


            $files = $request->file('files');

            foreach ($files as $index => $file) {
                $file->storeAs('workspaces/' . $workspace_id . '/news/' . $newsId, $index . '.' . $file->getClientOriginalExtension());
            }
        }

        $updated = $this->newsService->updateNews($newsId, [
            'title' => $validated['title'],
            'content' => $validated['content']
        ]);
        return response()->json([
            'message' => $updated ? 'News updated successfully.' : 'Failed to update news.',
        ]);
    }

    public function destroy(UpdateNewsRequest $request ,int $newId): JsonResponse
    {
        $request->validated();

        $news = $this->newsRepository->getById($newId);
        $workspace_id = $news->workspace->workspace_id;

        // Delete old images
        $files = Storage::disk('s3')->files('workspaces/' . $workspace_id . '/news/' . $newId);
        Storage::disk('s3')->delete($files);

        $deleted = $this->newsService->deleteNews($newId);
        return response()->json([
            'message' => $deleted ? 'News deleted successfully.' : 'Failed to delete news.',
        ]);
    }
}
