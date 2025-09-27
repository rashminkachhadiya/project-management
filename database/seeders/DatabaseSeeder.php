<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        \App\Models\User::factory()->count(3)->state(['role' => 'admin'])->create();
        \App\Models\User::factory()->count(3)->state(['role' => 'manager'])->create();
        \App\Models\User::factory()->count(5)->state(['role' => 'user'])->create();

        // Projects, tasks, comments
        \App\Models\Project::factory(5)
            ->has(\App\Models\Task::factory(2)
                ->has(\App\Models\Comment::factory(2))
            )
            ->create();
    }
}
