<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Traits\SimpleCRUD;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository
{
    use SimpleCRUD;

    protected string|Assignment $model = Assignment::class;
    
    public function findByWorkspaceId(int $workspaceId): Collection
    {
        return $this->model::where('workspace_id', $workspaceId)->get();
    }
}
