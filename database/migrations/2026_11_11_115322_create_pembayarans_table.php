
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel pesanan
            $table->foreignId('pesanan_id')
                  ->nullable()
                  ->constrained('pesanans')
                  ->nullOnDelete();

            $table->string('order_id', 60)->index();
            $table->string('transaction_id', 60)->nullable();
            $table->string('transaction_status', 30)->nullable();
            $table->string('fraud_status', 20)->nullable();
            $table->string('payment_type', 40)->nullable();
            $table->string('status_code', 5)->nullable();
            $table->decimal('gross_amount', 14, 2)->default(0);

            // Hasil pemeriksaan tanda tangan
            $table->boolean('signature_sah')->default(false);

            // Muatan notifikasi mentah
            $table->json('muatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
