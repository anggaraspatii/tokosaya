<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_id')
                  ->constrained('kategoris')
                  ->restrictOnDelete();

            $table->string('nama');
            $table->string('slug')->unique();
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi');
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
