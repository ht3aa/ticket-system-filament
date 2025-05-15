<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\ProjectPermission;
use App\Models\ProjectRole;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Create a project
        $project = Project::factory()->create([
            'title' => 'Test Project',
            'description' => 'This is a test project',
        ]);

        // Create project statuses
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
                $status
            );
        }

        // Create project labels
        $labels = [
            ['title' => 'Bug', 'description' => 'A bug that needs to be fixed', 'color' => '#ff0000'],
            ['title' => 'Feature', 'description' => 'A new feature request', 'color' => '#00ff00'],
            ['title' => 'Enhancement', 'description' => 'An improvement to existing functionality', 'color' => '#0000ff'],
            ['title' => 'Documentation', 'description' => 'Documentation related task', 'color' => '#ffff00'],
            ['title' => 'Urgent', 'description' => 'High priority task', 'color' => '#ff00ff'],
        ];

        foreach ($labels as $label) {
            ProjectLabel::firstOrCreate(
                ['title' => $label['title'], 'project_id' => $project->id],
                $label
            );
        }

        // Create project roles
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

        // Create project permissions
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
    }
}
