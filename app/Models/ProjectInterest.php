<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInterest extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'phone', 'email', 'purchase_type', 'purpose', 'unit_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
