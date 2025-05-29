<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketStaff;
use Illuminate\Database\Seeder;

class TicketStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::all();

        foreach ($tickets as $ticket) {
            // Create staff members for each ticket
            TicketStaff::factory(3)
                ->for($ticket, 'ticket')
                ->for($ticket->project->members->random(), 'projectMember')
                ->create();
        }
    }
}
