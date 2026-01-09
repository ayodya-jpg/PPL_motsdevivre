<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Nama tabel (opsional kalau sudah "addresses")
    // protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'nama_penerima',
        'no_hp',
        'alamat_lengkap',
        'kota',
        'provinsi',
        'kode_pos',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
