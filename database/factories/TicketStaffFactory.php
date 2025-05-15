<?php

namespace Database\Factories;

use App\Models\ProjectMember;
use App\Models\TicketInformation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketStaff>
 */
class TicketStaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => TicketInformation::factory(),
            'member_id' => ProjectMember::factory(),
            'type' => fake()->randomElement(['assigned', 'accountable', 'consulted', 'informed']),
        ];
    }
}
