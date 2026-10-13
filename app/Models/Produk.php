<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama',
        'slug',
        'harga',
        'deskripsi',
        'stok',
        'gambar',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}
