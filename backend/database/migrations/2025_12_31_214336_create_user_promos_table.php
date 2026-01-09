<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('promo_code'); // Contoh: NEWUSER20, SUBS_SHIP10
            $table->enum('promo_type', ['product', 'shipping']); // Kategori promo
            $table->integer('discount_percent'); // Angka diskon (misal: 20)
            $table->boolean('is_used')->default(false); // Status pemakaian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_promos');
    }
};
