<?php

namespace Database\Factories;

use App\Filament\Member\Resources\TicketResource\Enums\StaffType;
use App\Models\ProjectMember;
use App\Models\Ticket;
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
            'ticket_id' => Ticket::factory(),
            'project_member_id' => ProjectMember::factory(),
            'type' => fake()->randomElement(array_column(StaffType::cases(), 'value')),
        ];
    }
}
