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
    ];

    public function tickets()
    {
        return $this->hasMany(TicketInformation::class, 'label_id');
    }

    public function statuses()
    {
        return $this->belongsToMany(ProjectStatus::class, 'project_labels_statuses', 'label_id', 'status_id');
    }
}
