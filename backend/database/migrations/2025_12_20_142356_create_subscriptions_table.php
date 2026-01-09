<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            // Info paket - HANYA 1 PAKET: PELAKU USAHA
            $table->string('plan_name')->default('Paket Pelaku Usaha');
            $table->integer('price_per_month')->default(500000); // Rp 500.000/bulan

            // Status langganan
            $table->enum('status', [
                'pending_payment',  // sudah pilih paket, menunggu bayar
                'active',           // langganan aktif
                'expired',          // masa langganan habis
                'cancelled',        // dibatalkan
            ])->default('pending_payment');

            // Periode langganan
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // Tracking Midtrans
            $table->string('midtrans_order_id')->nullable();
            $table->string('snap_token')->nullable();

            // ✅ FITUR MEMBERSHIP PELAKU USAHA
            $table->boolean('has_analytics_access')->default(false); // Akses Analytics
            $table->integer('product_slot_limit')->default(0);       // 0 = unlimited

            $table->timestamps();

            // Relasi ke users
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
