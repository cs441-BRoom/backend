<?php

namespace App\Services;

use App\Models\Workspace;
use App\Repositories\WorkspaceMemberRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class WorkspaceService
{
    private WorkspaceRepository $workspaceRepository;
    private WorkspaceMemberRepository $workspaceMemberRepository;

    public function __construct(WorkspaceRepository $workspaceRepository, WorkspaceMemberRepository $workspaceMemberRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
        $this->workspaceMemberRepository = $workspaceMemberRepository;
    }

    public function create(array $data): Workspace
    {
        do {
            $joinCode = Str::upper(Str::random(10));
        } while ($this->workspaceRepository->findByJoinCode($joinCode));

        $data['join_code'] = $joinCode;

        return $this->workspaceRepository->create($data);
    }

    public function join(int $userId, string $joinCode): Workspace
    {

        $workspace = $this->workspaceRepository->findByJoinCode($joinCode);
        if (!$workspace) {
            throw new ModelNotFoundException('Invalid join code.');
        }

        if ($workspace->created_by == $userId) {
            throw new \Exception('You cannot join the workspace you created.');
        }

        $workspace->users()->attach($userId, ['role' => 'member']);

        return $workspace;
    }

    public function getMyWorkspaces(int $userId)
    {
        return $this->workspaceRepository->findByOwner($userId);
    }


    public function getJoinedWorkspaces(int $userId)
    {
        return $this->workspaceRepository->findJoinedWorkspacesByUserId($userId);
    }

    public function leaveWorkspace(int $userId, int $workspaceId)
    {
        $member = $this->workspaceMemberRepository->findByUserAndWorkspace($userId, $workspaceId);

        if (!$member) {
            throw new ModelNotFoundException('User is not a member of this workspace.');
        }

        $this->workspaceMemberRepository->removeMemberFromWorkspace($workspaceId, $userId);
    }
}
