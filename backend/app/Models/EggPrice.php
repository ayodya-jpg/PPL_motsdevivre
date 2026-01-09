<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggPrice extends Model
{
    protected $table = 'egg_prices';

    protected $fillable = [
        'plan_name',
        'date',
        'price_per_kg',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
