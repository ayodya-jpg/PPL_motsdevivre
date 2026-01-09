<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_harga',
        'status',
        'snap_token',
        'transaction_id',
        'payment_type',
        'paid_at'
    ];

    // Relasi ke Detail Order
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
