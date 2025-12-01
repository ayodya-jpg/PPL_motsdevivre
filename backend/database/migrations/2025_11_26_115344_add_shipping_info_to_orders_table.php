<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('payment_method')->nullable()->after('status');
        $table->string('shipping_method')->nullable()->after('payment_method'); // Misal: "JNE Reguler"
        $table->integer('shipping_cost')->default(0)->after('shipping_method');
        $table->string('shipping_estimation')->nullable()->after('shipping_cost'); // Misal: "3-4 Hari"
        $table->text('delivery_address')->nullable()->after('shipping_estimation');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['payment_method', 'shipping_method', 'shipping_cost', 'shipping_estimation', 'delivery_address']);
    });
}
};
