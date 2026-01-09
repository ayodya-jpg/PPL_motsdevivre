<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model {
    protected $fillable = ['user_id', 'nama_penerima', 'no_hp', 'alamat_lengkap', 'kota'];
}
