<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLabel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'color',
        'project_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function hasChildren(): bool
    {
        return $this->tickets_exists > 0;
    }
}
