<?php

namespace App\Models;

use App\Models\Scopes\TicketScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;

#[ScopedBy(TicketScope::class)]
class Ticket extends Model
{
    use HasFactory, HasFilamentComments, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'code',
        'parent_id',
        'project_status_id',
        'project_id',
        'project_label_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function parent()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function children()
    {
        return $this->hasMany(Ticket::class);
    }

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function projectLabel()
    {
        return $this->belongsTo(ProjectLabel::class);
    }

    public function staff()
    {
        return $this->hasMany(TicketStaff::class);
    }
}
