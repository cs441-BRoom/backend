<?php

namespace App\Http\Resources\Assignments;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'assignment_id' => $this->assignment_id,
            'workspace_id' => $this->workspace_id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'created_by' => $this->created_by,
            'submitted_number' => (int)$this->submitted_number,
            'members' => (int)$this->members,
            'submission_date' => $this->submit_at,
            'score' => (int)$this->score,
            'created_by_full_name' => $this->created_by_full_name,
            'created_by_username' => $this->created_by_username
        ];
    }
}
