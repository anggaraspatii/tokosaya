
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Kolom provinsi sudah tersedia, jadi tidak dibuat lagi.
            $table->string('kecamatan', 100)->nullable();
            $table->unsignedInteger('kecamatan_id')->nullable();

            $table->string('kurir', 20)->nullable();
            $table->string('layanan_kurir', 50)->nullable();
            $table->string('estimasi_kirim', 50)->nullable();
            $table->unsignedInteger('berat_total')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn([
                'kecamatan',
                'kecamatan_id',
                'kurir',
                'layanan_kurir',
                'estimasi_kirim',
                'berat_total',
            ]);
        });
    }
};
