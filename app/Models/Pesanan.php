<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    protected $fillable = [
        'nomor_pesanan',
        'nama_pemesan',
        'telepon',
        'alamat',
        'catatan',
        'total_harga',
        'status',

        // Kolom pembayaran Midtrans
        'status_bayar',
        'midtrans_order_id',
        'midtrans_redirect_url',
        'batas_bayar',
        'metode_bayar',
        'dibayar_pada',
        'stok_dikembalikan',

        // Kolom pengiriman
        'provinsi',
        'kota',
        'kode_pos',
        'kecamatan',
        'kecamatan_id',
        'kurir',
        'layanan_kurir',
        'estimasi_kirim',
        'berat_total',
        'ongkir',

    ];

    protected $casts = [
        'batas_bayar'       => 'datetime',
        'dibayar_pada'      => 'datetime',
        'stok_dikembalikan' => 'boolean',
        'ongkir' => 'integer',
    ];

    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    /** Riwayat notifikasi pembayaran pesanan ini */
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class)->latest();
    }

    /** Benar bila uangnya sudah masuk */
    public function sudahLunas(): bool
    {
        return $this->status_bayar === 'lunas';
    }

    /** Benar bila pesanan ini masih boleh dibayar */
  public function bisaDibayar(): bool
{
    return in_array($this->status_bayar, [
        'belum_bayar',
        'pending',
    ], true)
    && $this->status !== 'dibatalkan';
}

    /** Label dan jenis warnanya */
    public function labelBayar(): array
    {
        return match ($this->status_bayar) {
            'lunas'       => [__('web.lunas'), 'success'],
            'pending'     => [__('web.menunggu'), 'warning'],
            'gagal'       => [__('web.gagal'), 'danger'],
            'kedaluwarsa' => [__('web.kedaluwarsa'), 'secondary'],
            default       => [__('web.belum_dibayar'), 'secondary'],
        };
    }

/**
 * Mengembalikan stok produk ke etalase.
 * Stok hanya dikembalikan satu kali.
 * Method ini harus dipanggil di dalam DB::transaction().
 */
public function kembalikanStok(): void
{
    // Jika stok sudah pernah dikembalikan, hentikan proses.
    if ($this->stok_dikembalikan) {
        return;
    }

    // Ambil detail barang pesanan.
    $this->loadMissing('detailPesanans');

    foreach ($this->detailPesanans as $baris) {

        // Lewati jika produk sudah dihapus.
        if (! $baris->produk_id) {
            continue;
        }

        // Kunci data produk agar tidak terjadi konflik stok.
        $produk = Produk::lockForUpdate()->find($baris->produk_id);

        if ($produk) {
            $produk->increment('stok', $baris->jumlah);
        }
    }

    // Tandai bahwa stok sudah dikembalikan.
    $this->stok_dikembalikan = true;
}

}
