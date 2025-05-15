<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(ProjectPermission::class, 'project_roles_permissions', 'role_id', 'permission_id')
            ->withPivot('project_id');
    }
}
