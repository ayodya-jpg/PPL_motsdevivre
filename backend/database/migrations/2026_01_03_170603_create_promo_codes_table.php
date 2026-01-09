<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode promo (contoh: MEMBER10)
            $table->enum('type', ['percentage', 'fixed']); // Tipe diskon
            $table->decimal('value', 10, 2); // Nilai diskon (10 = 10% atau Rp 10.000)
            $table->decimal('min_purchase', 10, 2)->default(0); // Minimal belanja
            $table->integer('usage_limit')->nullable(); // Batas penggunaan (null = unlimited)
            $table->integer('usage_count')->default(0); // Sudah dipakai berapa kali
            $table->boolean('members_only')->default(true); // Khusus member?
            $table->date('valid_from')->nullable(); // Mulai berlaku
            $table->date('valid_until')->nullable(); // Sampai kapan
            $table->boolean('is_active')->default(true); // Aktif/tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
