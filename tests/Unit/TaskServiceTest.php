<?php

namespace Tests\Unit;

use App\Jobs\SendTaskAssignedNotification;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
    }

    public function test_creates_task_with_correct_defaults(): void
    {
        $workspace = Workspace::factory()->create();
        $creator   = User::factory()->create();

        $task = $this->service->create($workspace, $creator, [
            'title' => 'New Task',
        ]);

        $this->assertEquals('New Task', $task->title);
        $this->assertEquals(Task::STATUS_PENDING, $task->status);
        $this->assertEquals(Task::PRIORITY_MEDIUM, $task->priority);
        $this->assertEquals($creator->id, $task->created_by);
    }

    public function test_does_not_notify_when_creator_assigns_to_themselves(): void
    {
        Queue::fake();

        $workspace = Workspace::factory()->create();
        $creator   = User::factory()->create();

        $this->service->create($workspace, $creator, [
            'title'       => 'Self-assigned task',
            'assigned_to' => $creator->id,
        ]);

        Queue::assertNotPushed(SendTaskAssignedNotification::class);
    }

    public function test_notifies_when_assigned_to_different_user(): void
    {
        Queue::fake();

        $workspace = Workspace::factory()->create();
        $creator   = User::factory()->create();
        $assignee  = User::factory()->create();

        $this->service->create($workspace, $creator, [
            'title'       => 'Assigned task',
            'assigned_to' => $assignee->id,
        ]);

        Queue::assertPushed(SendTaskAssignedNotification::class);
    }

    public function test_reassigning_task_dispatches_new_notification(): void
    {
        Queue::fake();

        $workspace   = Workspace::factory()->create();
        $creator     = User::factory()->create();
        $newAssignee = User::factory()->create();

        $task = Task::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by'   => $creator->id,
            'assigned_to'  => null,
        ]);

        $this->service->assign($task, $newAssignee->id);

        Queue::assertPushed(SendTaskAssignedNotification::class);
    }

    public function test_no_notification_when_assigning_to_same_user(): void
    {
        Queue::fake();

        $assignee = User::factory()->create();
        $task     = Task::factory()->create(['assigned_to' => $assignee->id]);

        $this->service->assign($task, $assignee->id);

        Queue::assertNotPushed(SendTaskAssignedNotification::class);
    }
}
