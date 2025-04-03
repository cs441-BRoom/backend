<?php

namespace App\Http\Resources\Submissions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'submission_id' => $this->submission_id,
            'assignment_id' => $this->assignment_id,
            'user_id' => $this->user_id,
            'score' => $this->score,
            'submit_at' => $this->submit_at,
            'user_full_name' => $this->user_full_name,
            'user_username' => $this->user_username,
        ];
    }
}
