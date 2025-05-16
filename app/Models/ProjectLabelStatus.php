<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLabelStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_status_id',
        'project_label_id',
    ];

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function projectLabel()
    {
        return $this->belongsTo(ProjectLabel::class);
    }
}
