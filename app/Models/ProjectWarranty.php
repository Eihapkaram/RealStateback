<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectWarranty extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'warranty_name', 'duration'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
