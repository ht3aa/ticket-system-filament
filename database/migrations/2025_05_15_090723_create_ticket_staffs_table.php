<?php

use App\Filament\Member\Resources\TicketResource\Enums\StaffType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('project_member_id')->constrained('project_members')->cascadeOnDelete();
            $table->enum('type', array_column(StaffType::cases(), 'value'))->default(StaffType::ASSIGNEE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_staff');
    }
};
