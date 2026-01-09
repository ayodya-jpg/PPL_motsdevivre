<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // IZINKAN KOLOM INI DISIMPAN
    protected $fillable = [
        'order_id',
        'product_id',
        'jumlah',
        'harga_saat_ini'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}