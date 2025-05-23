<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // create hasan user
        \App\Models\User::factory()->create([
            'name' => 'Hasan Tahseen',
            'email' => 'hasan@example.com',
            'password' => 'password',
        ]);

        // Create regular users
        \App\Models\User::factory(10)->create();

        // Create projects with their related data
        \App\Models\Project::factory(5)
            ->has(\App\Models\ProjectRole::factory(3), 'roles')
            ->has(\App\Models\ProjectMember::factory(5)->state(fn (array $attributes, $project) => ['project_id' => $project]), 'members')
            ->has(\App\Models\ProjectStatus::factory(4), 'statuses')
            ->create()
            ->each(function ($project) {
                // Create labels for the project
                $labelTypes = ['Bug', 'Feature', 'Enhancement', 'Documentation', 'Urgent'];
                foreach ($labelTypes as $index => $type) {
                    \App\Models\ProjectLabel::factory()->create([
                        'title' => $type.' '.($project->id * 100 + $index + 1),
                        'project_id' => $project->id,
                    ]);
                }

                // Create permissions for the project
                $permissions = \App\Models\ProjectPermission::factory(5)->create(['project_id' => $project->id]);
                // Attach permissions to each role with project_id in the pivot
                $project->roles->each(function ($role) use ($project) {
                    $role->projectPermissions()->attach(
                        $project->permissions->pluck('id')->toArray(),
                        ['project_id' => $project->id]
                    );
                });
                // Create tickets for each project
                \App\Models\Ticket::factory(10)
                    ->for($project)
                    ->for($project->statuses->random(), 'projectStatus')
                    ->for($project->labels->random(), 'projectLabel')
                    ->create()
                    ->each(function ($ticket) use ($project) {
                        // Create staff members for each ticket
                        \App\Models\TicketStaff::factory(3)
                            ->for($ticket, 'ticket')
                            ->for($project->members->random(), 'projectMember')
                            ->create();
                    });
            });
    }
}
