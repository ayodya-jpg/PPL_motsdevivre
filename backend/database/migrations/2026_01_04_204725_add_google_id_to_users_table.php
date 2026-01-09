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
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->after('email');
        // Ubah password jadi nullable (opsional, jika database mengizinkan perubahan struktur)
        // Jika tidak bisa diubah lewat migration, pastikan saat insert kita beri password dummy.
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('google_id');
    });
}
};
