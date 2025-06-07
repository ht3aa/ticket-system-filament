<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_role_id',
        'project_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projectRole()
    {
        return $this->belongsTo(ProjectRole::class);
    }

    public function ticketStaff()
    {
        return $this->hasMany(TicketStaff::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
