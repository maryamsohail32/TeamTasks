<?php
namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy {
    public function view(User $user, Team $team): bool {
        return $team->members->contains($user);
    }

    public function update(User $user, Team $team): bool {
        return $team->owner_id === $user->id;
    }
}