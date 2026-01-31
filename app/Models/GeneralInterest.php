<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralInterest extends Model
{
    use HasFactory;

    protected $table = 'general_interests';

    protected $fillable = [
        'name',
        'phone',
        'max_price',
        'property_type',
        'finance_type',
        'district',
        'beds',
        'baths',
    ];
}
