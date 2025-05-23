<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProjectLabelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseTitles = ['Bug', 'Feature', 'Enhancement', 'Documentation', 'Urgent'];
        $baseTitle = fake()->randomElement($baseTitles);

        return [
            'title' => $baseTitle.' '.fake()->unique()->numberBetween(1, 1000),
            'description' => fake()->sentence(),
            'color' => fake()->hexColor(),
            'project_id' => Project::factory(),
        ];
    }
}
