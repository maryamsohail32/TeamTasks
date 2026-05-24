<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskAssignedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Retry up to 3 times before failing
    public int $tries = 3;

    // Wait 60 seconds before retrying
    public int $backoff = 60;

    public function __construct(private readonly Task $task)
    {
    }

    public function handle(): void
    {
        // Fresh load to ensure we have latest state and relationships
        $task = $this->task->fresh(['assignee', 'creator', 'workspace']);

        // Guard: task might have been reassigned or deleted before job ran
        if (! $task || ! $task->assignee) {
            return;
        }

        $task->assignee->notify(new TaskAssignedNotification($task));
    }
}
