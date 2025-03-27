<?php

namespace App\Repositories;

use App\Models\WorkspaceMember;
use App\Repositories\Traits\SimpleCRUD;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceMemberRepository
{
    use SimpleCRUD;

    protected string|WorkspaceMember $model = WorkspaceMember::class;

    public function __construct(WorkspaceMember $workspace)
    {
        $this->model = $workspace;
    }

    public function getMembersByWorkspaceId(int $workspaceId): Collection
    {
        return $this->model::where('workspace_id', $workspaceId)->get();
    }

    public function removeMemberFromWorkspace(int $workspaceId, int $userId)
    {
        return $this->model::where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->forceDelete();
    }

    public function findByUserAndWorkspace(int $userId, int $workspaceId)
    {
        return $this->model::where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->first();
    }

}
