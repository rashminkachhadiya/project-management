<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['created_by' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/tasks/{$task->id}/comments", [
            'body' => 'This is a test comment'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('comments', ['body' => 'This is a test comment']);
    }
}
