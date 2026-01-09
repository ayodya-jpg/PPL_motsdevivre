<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPromo extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar (sesuai gambar phpMyAdmin kamu)
    protected $table = 'user_promos';

    protected $fillable = [
        'user_id',
        'promo_code',
        'promo_type',
        'discount_percent',
        'is_used'
    ];
}
