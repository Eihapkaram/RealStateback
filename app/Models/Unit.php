<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'unit_type_id',
        'living_rooms',
        'bedrooms',
        'bathrooms',
        'area',
        'price',
        'status',
        'title', // 👈 جديد
        'description',
        'floor',
    ];

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }
    public function interests()
{
    return $this->hasMany(ProjectInterest::class);
}

}
