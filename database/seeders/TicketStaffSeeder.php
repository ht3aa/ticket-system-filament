<?php

namespace Database\Seeders;

use App\Models\ProjectMember;
use App\Models\Scopes\TicketScope;
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
        $tickets = Ticket::withoutGlobalScopes([TicketScope::class])->get();

        foreach ($tickets as $ticket) {
            // Create tickets for each project
            TicketStaff::factory(10)
                ->create([
                    'ticket_id' => $ticket->id,
                    'project_member_id' => $ticket->project->members->first() ?? ProjectMember::factory()->create()
                ]);
        }
    }
}
