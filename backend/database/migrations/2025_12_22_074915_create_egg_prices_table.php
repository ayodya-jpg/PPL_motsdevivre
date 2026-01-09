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
    Schema::create('egg_prices', function (Blueprint $table) {
        $table->id();
        $table->string('plan_name');      // contoh: "Paket Hemat"
        $table->date('date');             // tanggal harga berlaku
        $table->integer('price_per_kg');  // harga 1 kg dalam rupiah
        $table->timestamps();

        $table->index(['plan_name', 'date']);
    });
}

public function down()
{
    Schema::dropIfExists('egg_prices');
}
};
