<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'project_id',
        'unit',
        'request_type',
        'unit_received',
        'message',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
