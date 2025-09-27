<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable): array
    {
        // You can use ['mail', 'database'] if both needed
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New Task Assigned')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have been assigned a new task: ' . $this->task->title)
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Please check it as soon as possible.');
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title'   => $this->task->title,
            'status'  => $this->task->status,
        ];
    }
}
