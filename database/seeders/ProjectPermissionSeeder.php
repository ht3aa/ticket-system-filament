<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectPermission;
use Illuminate\Database\Seeder;

class ProjectPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Create predefined permissions for each project
            $permissions = [
                ['title' => 'Create Ticket', 'description' => 'Can create new tickets'],
                ['title' => 'Edit Ticket', 'description' => 'Can edit existing tickets'],
                ['title' => 'Delete Ticket', 'description' => 'Can delete tickets'],
                ['title' => 'View Tickets', 'description' => 'Can view tickets'],
                ['title' => 'Manage Users', 'description' => 'Can manage project users'],
            ];

            foreach ($permissions as $permission) {
                ProjectPermission::firstOrCreate(
                    [
                        'title' => $permission['title'],
                        'project_id' => $project->id,
                    ],
                    [
                        ...$permission,
                        'project_id' => $project->id,
                    ]
                );
            }

            // Create additional random permissions
            ProjectPermission::factory(3)->create(['project_id' => $project->id]);

            // Attach permissions to each role with project_id in the pivot
            $project->roles->each(function ($role) use ($project) {
                $role->projectPermissions()->attach(
                    $project->permissions->pluck('id')->toArray(),
                    ['project_id' => $project->id]
                );
            });
        }
    }
}
