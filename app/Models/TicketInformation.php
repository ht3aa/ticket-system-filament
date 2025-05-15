<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'code',
        'parent_id',
        'status_id',
        'project_id',
        'label_id',
    ];

    public function parent()
    {
        return $this->belongsTo(TicketInformation::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TicketInformation::class, 'parent_id');
    }

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function label()
    {
        return $this->belongsTo(ProjectLabel::class, 'label_id');
    }

    public function staff()
    {
        return $this->hasMany(TicketStaff::class);
    }
}
