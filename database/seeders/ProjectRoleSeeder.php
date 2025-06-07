<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectRole;
use Illuminate\Database\Seeder;

class ProjectRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Create predefined roles for each project
            $roles = [
                ['title' => 'Admin', 'description' => 'Project administrator'],
                ['title' => 'Developer', 'description' => 'Project developer'],
                ['title' => 'Tester', 'description' => 'Project tester'],
                ['title' => 'Project Manager', 'description' => 'Project manager'],
                ['title' => 'Viewer', 'description' => 'Project viewer'],
            ];

            foreach ($roles as $role) {
                ProjectRole::firstOrCreate(
                    [
                        'title' => $role['title'],
                        'project_id' => $project->id,
                    ],
                    [
                        ...$role,
                        'project_id' => $project->id,
                    ]
                );
            }

            // Create additional random roles
            ProjectRole::factory(2)->create(['project_id' => $project->id]);
        }
    }
}
