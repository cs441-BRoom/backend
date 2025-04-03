<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Database\Eloquent\Collection;

class NewsService
{
    private NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getAllNewsByWorkspace(int $workspaceId): Collection
    {
        return $this->newsRepository->findByWorkspaceId($workspaceId);
    }

    public function createNews(array $data): News
    {
        $user = auth()->user();

        $attributes = [
            'workspace_id' => $data['workspace_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'created_by' => $user->user_id,
        ];

        return $this->newsRepository->create($attributes);
    }

    public function updateNews(int $newsId, array $data): bool
    {
        return $this->newsRepository->update($data, $newsId);
    }

    public function deleteNews(int $newsId): bool
    {
        return $this->newsRepository->delete($newsId);
    }

    public function getNewsById(int $workspaceId, int $newsId): ?News
    {
        return $this->newsRepository->findNewsById($workspaceId, $newsId);
    }



}
