<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New task assigned: {$this->task->title}")
            ->greeting("Hi {$notifiable->name},")
            ->line("You've been assigned a new task in the **{$this->task->workspace->name}** workspace.")
            ->line("**Task:** {$this->task->title}")
            ->line("**Priority:** " . ucfirst($this->task->priority))
            ->when($this->task->due_date, fn($mail) => $mail->line("**Due:** {$this->task->due_date->format('M d, Y')}"))
            ->when($this->task->description, fn($mail) => $mail->line($this->task->description))
            ->action('View Task', url("/tasks/{$this->task->id}"))
            ->line("Assigned by: {$this->task->creator->name}");
    }
}
