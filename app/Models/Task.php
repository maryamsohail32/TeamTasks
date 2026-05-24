<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model {
    protected $fillable = ['team_id','creator_id','assignee_id','title','description','status','priority','due_date'];
    protected $casts = ['due_date' => 'date'];

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }
    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function assignee(): BelongsTo {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    public function isOverdue(): bool {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'done';
    }
}