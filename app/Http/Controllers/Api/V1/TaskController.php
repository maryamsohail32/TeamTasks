<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use App\Models\Workspace;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function index(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('view', $workspace);

        $tasks = $this->taskService->getForWorkspace($workspace, $request->all());

        return response()->json(new TaskCollection($tasks));
    }

    public function store(StoreTaskRequest $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('view', $workspace);

        $task = $this->taskService->create($workspace, $request->user(), $request->validated());

        return response()->json([
            'data' => new TaskResource($task->load('assignee', 'creator')),
        ], 201);
    }

    public function show(Request $request, Workspace $workspace, Task $task): JsonResponse
    {
        $this->authorize('view', $workspace);
        $this->authorize('view', $task);

        return response()->json([
            'data' => new TaskResource($task->load('assignee', 'creator')),
        ]);
    }

    public function update(UpdateTaskRequest $request, Workspace $workspace, Task $task): JsonResponse
    {
        $this->authorize('view', $workspace);
        $this->authorize('update', $task);

        $task = $this->taskService->update($task, $request->validated());

        return response()->json([
            'data' => new TaskResource($task->load('assignee', 'creator')),
        ]);
    }

    public function destroy(Request $request, Workspace $workspace, Task $task): JsonResponse
    {
        $this->authorize('view', $workspace);
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(null, 204);
    }

    public function assign(AssignTaskRequest $request, Workspace $workspace, Task $task): JsonResponse
    {
        $this->authorize('view', $workspace);
        $this->authorize('update', $task);

        $task = $this->taskService->assign($task, $request->validated('user_id'));

        return response()->json([
            'data'    => new TaskResource($task->load('assignee', 'creator')),
            'message' => 'Task assigned successfully.',
        ]);
    }
}
