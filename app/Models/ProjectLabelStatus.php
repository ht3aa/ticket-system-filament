<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLabelStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status_id',
        'label_id',
    ];

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function label()
    {
        return $this->belongsTo(ProjectLabel::class, 'label_id');
    }
}
