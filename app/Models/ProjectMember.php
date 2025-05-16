<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_member_id',
        'project_role_id',
        'project_id',
    ];

    public function projectMember()
    {
        return $this->belongsTo(User::class, 'project_member_id');
    }

    public function projectRole()
    {
        return $this->belongsTo(ProjectRole::class);
    }

    public function ticketStaff()
    {
        return $this->hasMany(TicketStaff::class, 'project_member_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
