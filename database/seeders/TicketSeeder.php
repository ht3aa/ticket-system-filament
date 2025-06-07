<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Create tickets for each project
            Ticket::factory(10)
                ->for($project)
                ->for($project->statuses->random(), 'projectStatus')
                ->for($project->labels->random(), 'projectLabel')
                ->create();
        }
    }
}
