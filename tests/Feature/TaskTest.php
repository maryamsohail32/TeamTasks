<?php

namespace Tests\Feature;

use App\Jobs\SendTaskAssignedNotification;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $member;
    private User $outsider;
    private Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner    = User::factory()->create();
        $this->member   = User::factory()->create();
        $this->outsider = User::factory()->create();

        $this->workspace = Workspace::factory()->create(['owner_id' => $this->owner->id]);
        $this->workspace->members()->attach($this->owner->id, ['role' => 'owner']);
        $this->workspace->members()->attach($this->member->id, ['role' => 'member']);
    }

    public function test_workspace_member_can_list_tasks(): void
    {
        Task::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->owner->id,
        ]);

        $this->actingAs($this->member)
            ->getJson("/api/v1/workspaces/{$this->workspace->id}/tasks")
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_outsider_cannot_list_tasks(): void
    {
        $this->actingAs($this->outsider)
            ->getJson("/api/v1/workspaces/{$this->workspace->id}/tasks")
            ->assertForbidden();
    }

    public function test_member_can_create_task(): void
    {
        $response = $this->actingAs($this->member)
            ->postJson("/api/v1/workspaces/{$this->workspace->id}/tasks", [
                'title'    => 'Write unit tests',
                'priority' => 'high',
                'due_date' => now()->addDays(5)->toDateString(),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Write unit tests')
            ->assertJsonPath('data.priority', 'high');

        $this->assertDatabaseHas('tasks', [
            'title'        => 'Write unit tests',
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->member->id,
        ]);
    }

    public function test_creating_task_with_invalid_assignee_fails(): void
    {
        $this->actingAs($this->owner)
            ->postJson("/api/v1/workspaces/{$this->workspace->id}/tasks", [
                'title'       => 'Test task',
                'assigned_to' => $this->outsider->id, // not a member!
            ])->assertStatus(422)
            ->assertJsonValidationErrors(['assigned_to']);
    }

    public function test_assigning_task_dispatches_queued_notification(): void
    {
        Queue::fake();

        $task = Task::factory()->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->owner->id,
        ]);

        $this->actingAs($this->owner)
            ->patchJson("/api/v1/workspaces/{$this->workspace->id}/tasks/{$task->id}/assign", [
                'user_id' => $this->member->id,
            ])->assertOk();

        Queue::assertPushed(SendTaskAssignedNotification::class, function ($job) use ($task) {
            return $job->task->id === $task->id;
        });
    }

    public function test_task_creator_can_update_task(): void
    {
        $task = Task::factory()->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->member->id,
            'status'       => Task::STATUS_PENDING,
        ]);

        $this->actingAs($this->member)
            ->putJson("/api/v1/workspaces/{$this->workspace->id}/tasks/{$task->id}", [
                'status' => Task::STATUS_IN_PROGRESS,
            ])->assertOk()
            ->assertJsonPath('data.status', 'in_progress');
    }

    public function test_non_creator_cannot_update_task(): void
    {
        // outsider is not even a member, but member is — yet they didn't create the task
        $task = Task::factory()->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->owner->id,
        ]);

        $this->actingAs($this->member)
            ->putJson("/api/v1/workspaces/{$this->workspace->id}/tasks/{$task->id}", [
                'title' => 'Hijacked title',
            ])->assertForbidden();
    }

    public function test_workspace_owner_can_delete_any_task(): void
    {
        $task = Task::factory()->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->member->id,
        ]);

        $this->actingAs($this->owner)
            ->deleteJson("/api/v1/workspaces/{$this->workspace->id}/tasks/{$task->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        Task::factory()->count(2)->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->owner->id,
            'status'       => Task::STATUS_PENDING,
        ]);

        Task::factory()->create([
            'workspace_id' => $this->workspace->id,
            'created_by'   => $this->owner->id,
            'status'       => Task::STATUS_COMPLETED,
        ]);

        $this->actingAs($this->owner)
            ->getJson("/api/v1/workspaces/{$this->workspace->id}/tasks?status=pending")
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
