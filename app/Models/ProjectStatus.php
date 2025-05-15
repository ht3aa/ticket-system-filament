<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
    ];

    public function tickets()
    {
        return $this->hasMany(TicketInformation::class, 'status_id');
    }

    public function labels()
    {
        return $this->belongsToMany(ProjectLabel::class, 'project_labels_statuses', 'status_id', 'label_id');
    }
}
