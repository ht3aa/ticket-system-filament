<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
    ];

    public function roles()
    {
        return $this->hasMany(ProjectRole::class);
    }

    public function permissions()
    {
        return $this->hasMany(ProjectPermission::class);
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the statuses associated with the project.
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(ProjectStatus::class);
    }

    /**
     * Get the labels associated with the project.
     */
    public function labels(): HasMany
    {
        return $this->hasMany(ProjectLabel::class);
    }

    public function labelsStatuses()
    {
        return $this->hasMany(ProjectLabelStatus::class);
    }
}
