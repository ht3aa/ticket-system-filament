<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Database\Seeder;

class ProjectMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Create project members
            ProjectMember::factory(5)->create(['project_id' => $project->id]);
        }
    }
}
