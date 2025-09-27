<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TaskAssignmentService;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigns_task_to_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $service = new TaskAssignmentService();
        $service->assign($task, $user->id);

        $this->assertEquals($user->id, $task->fresh()->assigned_to);
    }
}
