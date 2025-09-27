<?php

namespace Tests\Feature\Tasks;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_update_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $manager->id]);

        Sanctum::actingAs($manager);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 'done',
            'start_date' => '09/29/2025',
            'end_date' => '10/05/2025'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task']);
    }
}
