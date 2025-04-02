<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\JoinWorkspaceRequest;
use App\Http\Requests\Workspace\WorkspaceRequest;
use App\Http\Resources\Workspace\WorkspaceResource;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends Controller
{
    protected WorkspaceService $workspaceService;

    public function __construct(WorkspaceService $workspaceService)
    {
        $this->workspaceService = $workspaceService;
    }

    public function store(WorkspaceRequest $request): JsonResponse
    {
        $request->validated();
        $workspace = $this->workspaceService->create(array_merge(
            $request->validated(),
            ['created_by' => auth()->id()]
        ));

        return response()->json([
            'message' => 'Workspace created successfully.',
            'workspace' => new WorkspaceResource($workspace),
        ], 201);
    }

    public function join(JoinWorkspaceRequest $request): JsonResponse
    {
        $request->validated();
        try {
            $workspace = $this->workspaceService->join(auth()->id(), $request->join_code);

            return response()->json([
                'message' => 'Successfully joined workspace.',
                'workspace' => new WorkspaceResource($workspace),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getMyWorkspaces(): JsonResponse
    {
        $workspaces = $this->workspaceService->getMyWorkspaces(auth()->id());

        return response()->json([
            'message' => 'Fetched created workspaces.',
            'workspaces' => WorkspaceResource::collection($workspaces),
        ]);
    }

    public function getWorkspaceById(int $workspaceId): JsonResponse
    {
        $workspace = $this->workspaceService->getWorkspaceByID($workspaceId);
        return response()->json([
            'message' => 'Fetched workspace.',
            'workspace' => $workspace
        ]);
    }

    public function getJoinedWorkspaces(): JsonResponse
    {
        $workspaces = $this->workspaceService->getJoinedWorkspaces(auth()->id());

        return response()->json([
            'message' => 'Fetched joined workspaces.',
            'workspaces' => WorkspaceResource::collection($workspaces),
        ]);
    }

    public function leaveWorkspace(int $workspaceId): JsonResponse
    {
        try {
            $userId = auth()->id();
            $this->workspaceService->leaveWorkspace($userId, $workspaceId);

            return response()->json([
                'message' => 'Successfully left the workspace.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
