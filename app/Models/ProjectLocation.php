<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLocation extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'city', 'district', 'address', 'map_link'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
