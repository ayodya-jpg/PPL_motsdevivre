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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('snap_token')->nullable()->after('status');
        $table->string('transaction_id')->nullable()->after('snap_token');
        $table->string('payment_type')->nullable()->after('transaction_id');
        $table->timestamp('paid_at')->nullable()->after('payment_type');
    });
    }

    public function down()
    {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['snap_token', 'transaction_id', 'payment_type', 'paid_at']);
    });
    }
};
