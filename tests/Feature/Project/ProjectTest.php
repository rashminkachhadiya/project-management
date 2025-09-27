<?php

namespace Tests\Feature\Projects;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $payload = [
            'title' => 'New Project',
            'description' => 'Demo Project',
            'start_date' => '09/28/2025',
            'end_date' => '10/10/2025',
        ];

        $response = $this->postJson('/api/projects', $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('projects', ['title' => 'New Project']);
    }
}
