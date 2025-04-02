<?php

namespace App\Http\Resources\Workspace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $membersCount = $this->users()->count();
        return [
            'workspace_id' => $this->workspace_id,
            'name' => $this->name,
            'description' => $this->description,
            'join_code' => $this->join_code,
            'members_count' => $membersCount + 1,
            'created_by' => $this->user?->username, // ใช้ username จากความสัมพันธ์ user()
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
