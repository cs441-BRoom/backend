<?php

namespace App\Repositories;

use App\Models\News;
use App\Repositories\Traits\SimpleCRUD;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository
{
    use SimpleCRUD;

    protected string|News $model = News::class;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function findByWorkspaceId(int $workspaceId): Collection
    {
        return $this->model::withCount('comments')->where('workspace_id', $workspaceId)->get();
    }

    public function update(array $attributes, int $news_id)
    {
        return $this->model::where('news_id', $news_id)->update($attributes);
    }

    public function delete(int $id)
    {
        return $this->model::where('news_id', $id)->delete();
    }

    public function findNewsById(int $workspaceId, int $newsId): ?News
{
    return $this->model::where('workspace_id', $workspaceId)
        ->where('news_id', $newsId)
        ->withCount('comments')
        ->first();
}


}
