<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectStatus;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Create predefined statuses for each project
            $statuses = [
                ['title' => 'Open', 'description' => 'Ticket is open and needs attention'],
                ['title' => 'In Progress', 'description' => 'Ticket is being worked on'],
                ['title' => 'Review', 'description' => 'Ticket is under review'],
                ['title' => 'Done', 'description' => 'Ticket is completed'],
                ['title' => 'Closed', 'description' => 'Ticket is closed'],
            ];

            foreach ($statuses as $status) {
                ProjectStatus::firstOrCreate(
                    ['title' => $status['title'], 'project_id' => $project->id],
                    [...$status, 'project_id' => $project->id]
                );
            }

            // Create additional random statuses
            ProjectStatus::factory(2)->create(['project_id' => $project->id]);
        }
    }
}
