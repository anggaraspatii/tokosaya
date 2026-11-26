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
    Schema::table('pesanans', function (Blueprint $table) {
        $table->string('kota', 100)->nullable();
        $table->string('kode_pos', 10)->nullable();
        $table->unsignedInteger('ongkir')->default(0);
    });
}

public function down(): void
{
    Schema::table('pesanans', function (Blueprint $table) {
        $table->dropColumn([
            'kota',
            'kode_pos',
            'ongkir',
        ]);
    });
}
};
