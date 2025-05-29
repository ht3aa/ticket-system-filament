<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectLabel;
use Illuminate\Database\Seeder;

class ProjectLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Create predefined labels for each project
            $labels = [
                ['title' => 'Bug', 'description' => 'A bug that needs to be fixed', 'color' => '#ff0000'],
                ['title' => 'Feature', 'description' => 'A new feature request', 'color' => '#00ff00'],
                ['title' => 'Enhancement', 'description' => 'An improvement to existing functionality', 'color' => '#0000ff'],
                ['title' => 'Documentation', 'description' => 'Documentation related task', 'color' => '#ffff00'],
                ['title' => 'Urgent', 'description' => 'High priority task', 'color' => '#ff00ff'],
            ];

            foreach ($labels as $index => $label) {
                ProjectLabel::firstOrCreate(
                    ['title' => $label['title'], 'project_id' => $project->id],
                    [...$label, 'project_id' => $project->id]
                );
            }

            // Create additional random labels
            ProjectLabel::factory(3)->create(['project_id' => $project->id]);
        }
    }
}
