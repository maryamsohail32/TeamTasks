<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_workspace(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/v1/workspaces', [
                'name'        => 'My Team',
                'description' => 'A great workspace',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'My Team')
            ->assertJsonPath('data.owner.id', $user->id);

        $this->assertDatabaseHas('workspaces', ['name' => 'My Team', 'owner_id' => $user->id]);
    }

    public function test_user_only_sees_their_workspaces(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();

        Workspace::factory()->count(2)->create(['owner_id' => $user->id]);
        Workspace::factory()->create(['owner_id' => $other->id]); // should not appear

        $this->actingAs($user)
            ->getJson('/api/v1/workspaces')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_only_owner_can_update_workspace(): void
    {
        $owner  = User::factory()->create();
        $member = User::factory()->create();

        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $workspace->members()->attach($member->id, ['role' => 'member']);

        // Member should be forbidden
        $this->actingAs($member)
            ->putJson("/api/v1/workspaces/{$workspace->id}", ['name' => 'Hacked Name'])
            ->assertForbidden();

        // Owner should succeed
        $this->actingAs($owner)
            ->putJson("/api/v1/workspaces/{$workspace->id}", ['name' => 'Updated Name'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Name');
    }

    public function test_owner_can_invite_member_by_email(): void
    {
        $owner   = User::factory()->create();
        $invitee = User::factory()->create();

        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $workspace->members()->attach($owner->id, ['role' => 'owner']);

        $this->actingAs($owner)
            ->postJson("/api/v1/workspaces/{$workspace->id}/invite", [
                'email' => $invitee->email,
            ])->assertOk();

        $this->assertDatabaseHas('workspace_user', [
            'workspace_id' => $workspace->id,
            'user_id'      => $invitee->id,
        ]);
    }

    public function test_invite_with_nonexistent_email_fails(): void
    {
        $owner = User::factory()->create();
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

        $this->actingAs($owner)
            ->postJson("/api/v1/workspaces/{$workspace->id}/invite", [
                'email' => 'nobody@nowhere.com',
            ])->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_deleting_workspace_also_deletes_tasks(): void
    {
        $owner     = User::factory()->create();
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

        $workspace->tasks()->create([
            'title'      => 'Orphan task',
            'status'     => 'pending',
            'priority'   => 'low',
            'created_by' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->deleteJson("/api/v1/workspaces/{$workspace->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);
        $this->assertDatabaseMissing('tasks', ['workspace_id' => $workspace->id]);
    }
}
