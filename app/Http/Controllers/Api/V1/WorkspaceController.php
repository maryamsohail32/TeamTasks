<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\InviteMemberRequest;
use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceRequest;
use App\Http\Resources\Workspace\WorkspaceCollection;
use App\Http\Resources\Workspace\WorkspaceResource;
use App\Models\Workspace;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function __construct(private readonly WorkspaceService $workspaceService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $workspaces = $request->user()->allWorkspaces()->with('owner', 'members')->get();

        return response()->json(new WorkspaceCollection($workspaces));
    }

    public function store(StoreWorkspaceRequest $request): JsonResponse
    {
        $workspace = $this->workspaceService->create($request->user(), $request->validated());

        return response()->json([
            'data' => new WorkspaceResource($workspace->load('owner', 'members')),
        ], 201);
    }

    public function show(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('view', $workspace);

        return response()->json([
            'data' => new WorkspaceResource($workspace->load('owner', 'members')),
        ]);
    }

    public function update(UpdateWorkspaceRequest $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('update', $workspace);

        $workspace = $this->workspaceService->update($workspace, $request->validated());

        return response()->json([
            'data' => new WorkspaceResource($workspace->load('owner', 'members')),
        ]);
    }

    public function destroy(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('delete', $workspace);

        $this->workspaceService->delete($workspace);

        return response()->json(null, 204);
    }

    public function invite(InviteMemberRequest $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('invite', $workspace);

        $this->workspaceService->inviteMember($workspace, $request->validated('email'));

        return response()->json([
            'message' => 'Member invited successfully.',
        ]);
    }
}
