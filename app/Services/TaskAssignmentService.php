<?php

namespace App\Services;

use App\Models\Task;

class TaskAssignmentService
{
    /**
     * Assign a task to a user.
     *
     * @param  \App\Models\Task  $task
     * @param  int  $userId
     * @return \App\Models\Task
     */
    public function assign(Task $task, int $userId): Task
    {
        $task->assigned_to = $userId;
        $task->save();

        return $task;
    }
}
