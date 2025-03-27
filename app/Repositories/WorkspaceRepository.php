<?php

namespace App\Repositories;

use App\Models\Workspace;
use App\Repositories\Traits\SimpleCRUD;

class WorkspaceRepository
{
    use SimpleCRUD;

    protected string|Workspace $model = Workspace::class;

    public function __construct(Workspace $model)
    {
        $this->model = $model;
    }

    public function findByJoinCode(string $joinCode): ?Workspace
    {
        return $this->model::where('join_code', $joinCode)->first();
    }

    public function findByOwner(int $userId)
    {
        return $this->model::where('created_by', $userId)->get();
    }


    public function findJoinedWorkspacesByUserId(int $userId)
    {
        return $this->model::whereIn('workspace_id', function ($query) use ($userId) {
            $query->select('workspace_id')
                ->from('workspace_members')
                ->where('user_id', $userId);
        })->get();
    }

}
