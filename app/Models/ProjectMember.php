<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function role()
    {
        return $this->belongsTo(ProjectRole::class, 'role_id');
    }

    public function ticketStaff()
    {
        return $this->hasMany(TicketStaff::class, 'member_id');
    }
}
