<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test project
        Project::factory()->create([
            'title' => 'Test Project',
            'description' => 'This is a test project',
        ]);

        // Create additional projects
        Project::factory(500)->create();
    }
}
