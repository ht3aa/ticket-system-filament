<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectRolePermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_role_id',
        'project_permission_id',
        'project_id',
    ];

    public function projectRole()
    {
        return $this->belongsTo(ProjectRole::class);
    }

    public function projectPermission()
    {
        return $this->belongsTo(ProjectPermission::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
