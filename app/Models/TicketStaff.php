<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketStaff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'member_id',
        'type',
    ];

    /**
     * Get the ticket associated with this staff member.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketInformation::class, 'ticket_id');
    }

    /**
     * Get the project member associated with this ticket staff.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(ProjectMember::class);
    }
}
