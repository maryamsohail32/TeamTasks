<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    // Any member can view the workspace
    public function view(User $user, Workspace $workspace): bool
    {
        return $workspace->hasMember($user);
    }

    // Only the owner can update
    public function update(User $user, Workspace $workspace): bool
    {
        return $workspace->owner_id === $user->id;
    }

    // Only the owner can delete
    public function delete(User $user, Workspace $workspace): bool
    {
        return $workspace->owner_id === $user->id;
    }

    // Only the owner can invite members
    public function invite(User $user, Workspace $workspace): bool
    {
        return $workspace->owner_id === $user->id;
    }
}
