<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success', 'data' => ['token', 'name'], 'message'
            ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }
}
