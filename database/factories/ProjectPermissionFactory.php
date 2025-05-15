<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProjectPermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Create Ticket', 'Edit Ticket', 'Delete Ticket', 'View Tickets', 'Manage Users']),
            'description' => fake()->sentence(),
            'project_id' => Project::factory(),
        ];
    }
}
