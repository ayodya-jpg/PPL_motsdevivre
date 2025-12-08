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
        'payment_method',
        'shipping_method',
        'shipping_cost',
        'shipping_estimation',
        'delivery_address'
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
