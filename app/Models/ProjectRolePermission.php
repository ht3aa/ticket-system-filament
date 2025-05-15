<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectRolePermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'role_id',
        'permission_id',
        'project_id',
    ];

    public function role()
    {
        return $this->belongsTo(ProjectRole::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(ProjectPermission::class, 'permission_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
