<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'location',
        'status',
        'main_image',
        'description',
        'overview_bedrooms',
        'overview_bathrooms',
        'overview_kitchens',
        'area',
        'date',
    ];

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function features()
    {
        return $this->hasMany(ProjectFeature::class);
    }

    public function warranties()
    {
        return $this->hasMany(ProjectWarranty::class);
    }

    public function locationDetail()
    {
        return $this->hasOne(ProjectLocation::class);
    }

    public function interests()
    {
        return $this->hasMany(ProjectInterest::class);
    }

    public function unitTypes()
    {
        return $this->hasMany(UnitType::class);
    }

    public function units()
    {
        return $this->hasManyThrough(
            Unit::class,
            UnitType::class,
            'project_id',
            'unit_type_id'
        );
    }
}
