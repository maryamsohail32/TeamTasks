<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Workspaces this user owns
    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'owner_id');
    }

    // Workspaces this user is a member of (via pivot)
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // All workspaces accessible (owned + member)
    public function allWorkspaces()
    {
        $owned = $this->ownedWorkspaces()->pluck('id');
        $member = $this->workspaces()->pluck('workspaces.id');

        return Workspace::whereIn('id', $owned->merge($member)->unique());
    }

    // Tasks assigned to this user
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // Tasks created by this user
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    // Add inside the User class:
    public function teams(): BelongsToMany {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
}

    public function tasks(): HasMany {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();

}
}
