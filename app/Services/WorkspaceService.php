<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;

class WorkspaceService
{
    public function create(User $owner, array $data): Workspace
    {
        return DB::transaction(function () use ($owner, $data) {
            $workspace = Workspace::create([
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'owner_id'    => $owner->id,
            ]);

            // Owner is automatically added as a member with 'owner' role
            $workspace->members()->attach($owner->id, ['role' => 'owner']);

            return $workspace;
        });
    }

    public function update(Workspace $workspace, array $data): Workspace
    {
        $workspace->update($data);

        return $workspace->fresh();
    }

    public function delete(Workspace $workspace): void
    {
        DB::transaction(function () use ($workspace) {
            // Cascade delete tasks first, then detach members
            $workspace->tasks()->delete();
            $workspace->members()->detach();
            $workspace->delete();
        });
    }

    public function inviteMember(Workspace $workspace, string $email): void
    {
        $user = User::where('email', $email)->firstOrFail();

        // Prevent duplicate membership
        if (! $workspace->hasMember($user)) {
            $workspace->members()->attach($user->id, ['role' => 'member']);
        }
    }
}
