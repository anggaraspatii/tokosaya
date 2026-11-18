<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {

            // Status pembayaran
            $table->enum('status_bayar', [
                'belum_bayar',
                'pending',
                'lunas',
                'gagal',
                'kedaluwarsa',
            ])->default('belum_bayar')->after('status');

            // ID transaksi Midtrans
            $table->string('midtrans_order_id', 50)
                  ->nullable()
                  ->unique()
                  ->after('status_bayar');

            // URL pembayaran
            $table->string('midtrans_redirect_url')
                  ->nullable()
                  ->after('midtrans_order_id');

            // Batas waktu pembayaran
            $table->timestamp('batas_bayar')
                  ->nullable()
                  ->after('midtrans_redirect_url');

            // Metode pembayaran
            $table->string('metode_bayar', 40)
                  ->nullable()
                  ->after('batas_bayar');

            // Waktu pembayaran
            $table->timestamp('dibayar_pada')
                  ->nullable()
                  ->after('metode_bayar');

            // Penanda pengembalian stok
            $table->boolean('stok_dikembalikan')
                  ->default(false)
                  ->after('dibayar_pada');

            // Index status pembayaran
            $table->index('status_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {

            $table->dropIndex(['status_bayar']);

            $table->dropColumn([
                'status_bayar',
                'midtrans_order_id',
                'midtrans_redirect_url',
                'batas_bayar',
                'metode_bayar',
                'dibayar_pada',
                'stok_dikembalikan',
            ]);
        });
    }
};
