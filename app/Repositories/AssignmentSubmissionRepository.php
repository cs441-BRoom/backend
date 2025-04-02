<?php

namespace App\Repositories;

use App\Models\AssignmentSubmission;
use App\Repositories\Traits\SimpleCRUD;
use Illuminate\Database\Eloquent\Collection;

class AssignmentSubmissionRepository
{
    use SimpleCRUD;

    protected string|AssignmentSubmission $model = AssignmentSubmission::class;

    public function update(array $attributes, int $news_id)
    {
        return $this->model::where('submission_id', $news_id)->update($attributes);
    }

    public function findByAssignmentId(int $assignmentId): Collection
    {
        return $this->model::where('assignment_id', $assignmentId)->get();
    }

    public function findByAssignmentIdAndUserId(int $assignmentId, int $userId)
    {
        return $this->model::where('assignment_id', $assignmentId)->where('user_id', $userId)->first();
    }
}
