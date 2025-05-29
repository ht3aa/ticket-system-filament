<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectMember>
 */
class ProjectMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'project_role_id' => ProjectRole::factory(),
        ];
    }

    public function developer(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'project_role_id' => ProjectRole::factory()->create(['title' => 'Developer', 'project_id' => $attributes['project_id']]),
            ];
        });
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'project_role_id' => ProjectRole::factory()->create(['title' => 'Admin', 'project_id' => $attributes['project_id']]),
            ];
        });
    }

    public function tester(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'project_role_id' => ProjectRole::factory()->create(['title' => 'Tester', 'project_id' => $attributes['project_id']]),
            ];
        });
    }

    public function viewer(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'project_role_id' => ProjectRole::factory()->create(['title' => 'Viewer', 'project_id' => $attributes['project_id']]),
            ];
        });
    }

    public function projectManager(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'project_role_id' => ProjectRole::factory()->create(['title' => 'Project Manager', 'project_id' => $attributes['project_id']]),
            ];
        });
    }
}
