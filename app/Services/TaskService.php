<?php

namespace App\Services;

use App\Jobs\SendTaskAssignedNotification;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getForWorkspace(Workspace $workspace, array $filters = []): LengthAwarePaginator
    {
        $query = $workspace->tasks()->with('assignee', 'creator');

        // Filter by status
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by priority
        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        // Filter by assigned user
        if (! empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        // Show overdue tasks
        if (! empty($filters['overdue'])) {
            $query->overdue();
        }

        // Sorting
        $sortBy  = in_array($filters['sort_by'] ?? '', ['due_date', 'priority', 'created_at']) ? $filters['sort_by'] : 'created_at';
        $sortDir = ($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortDir)->paginate(15);
    }

    public function create(Workspace $workspace, User $creator, array $data): Task
    {
        $task = $workspace->tasks()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => Task::STATUS_PENDING,
            'priority'    => $data['priority'] ?? Task::PRIORITY_MEDIUM,
            'due_date'    => $data['due_date'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
            'created_by'  => $creator->id,
        ]);

        // Dispatch queued notification if someone is assigned
        if ($task->assigned_to && $task->assigned_to !== $creator->id) {
            SendTaskAssignedNotification::dispatch($task);
        }

        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $previousAssignee = $task->assigned_to;

        $task->update($data);
        $task->refresh();

        // Notify the new assignee if assignment changed
        if (
            isset($data['assigned_to']) &&
            $data['assigned_to'] !== $previousAssignee &&
            $task->assigned_to !== null
        ) {
            SendTaskAssignedNotification::dispatch($task);
        }

        return $task;
    }

    public function assign(Task $task, int $userId): Task
    {
        $previousAssignee = $task->assigned_to;

        $task->update(['assigned_to' => $userId]);
        $task->refresh();

        if ($userId !== $previousAssignee) {
            SendTaskAssignedNotification::dispatch($task);
        }

        return $task;
    }
}
