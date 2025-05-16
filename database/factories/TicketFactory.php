<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\ProjectStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'code' => fake()->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'parent_id' => null,
            'project_status_id' => ProjectStatus::factory(),
            'project_id' => Project::factory(),
            'project_label_id' => ProjectLabel::factory(),
        ];
    }
}
