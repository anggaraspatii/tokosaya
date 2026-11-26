<?php

namespace App\Services;

use App\Models\Pesanan;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class Midtrans
{
    /**
     * Batas waktu pembayaran dalam jam.
     */
    const BATAS_JAM = 24;

    /**
     * Membuat atau menggunakan kembali halaman pembayaran Midtrans.
     */
    public static function mintaHalamanBayar(Pesanan $pesanan): string
    {
        // 1. Periksa apakah pesanan masih boleh dibayar.
        if (! $pesanan->bisaDibayar()) {
            throw new RuntimeException(
                "Pesanan {$pesanan->nomor_pesanan} sudah tidak dapat dibayar."
            );
        }

        // 2. Gunakan kembali halaman pembayaran yang masih berlaku.
        if (
            $pesanan->status_bayar === 'pending'
            && $pesanan->midtrans_redirect_url
            && $pesanan->batas_bayar?->isFuture()
        ) {
            return $pesanan->midtrans_redirect_url;
        }

        // 3. Pastikan Server Key tersedia.
        $serverKey = config('midtrans.server_key');
        $snapUrl = config('midtrans.snap_url');

        if (empty($serverKey) || empty($snapUrl)) {
            throw new RuntimeException(
                'Konfigurasi Midtrans belum lengkap. Periksa Server Key dan Snap URL.'
            );
        }

        // 4. Buat order ID unik.
        $orderId = substr($pesanan->nomor_pesanan, 0, 30)
            . '-'
            . Str::upper(Str::random(10));

        // 5. Ambil detail produk.
        $pesanan->loadMissing('detailPesanans');


$rincian = [];

// Masukkan rincian produk terlebih dahulu.
foreach ($pesanan->detailPesanans as $baris) {
    $rincian[] = [
        'id'       => (string) ($baris->produk_id ?? 'X'),
        'name'     => Str::limit($baris->nama_produk, 47, '...'),
        'price'    => (int) round($baris->harga),
        'quantity' => (int) $baris->jumlah,
    ];
}

// Pastikan detail produk tersedia.
if (empty($rincian)) {
    throw new RuntimeException(
        'Detail produk pesanan tidak ditemukan.'
    );
}

// Tambahkan ongkos kirim dari RajaOngkir.
if ((int) round($pesanan->ongkir) > 0) {
    $rincian[] = [
        'id'       => 'ONGKIR',
        'name'     => Str::limit(
            "Ongkir {$pesanan->kurir} {$pesanan->layanan_kurir}",
            47,
            '...'
        ),
        'price'    => (int) round($pesanan->ongkir),
        'quantity' => 1,
    ];
}


        // 6. Periksa total pembayaran.
        $total = (int) round($pesanan->total_harga);

        $jumlahRincian = collect($rincian)->sum(
            fn ($baris) => $baris['price'] * $baris['quantity']
        );

        if ($jumlahRincian !== $total) {
            throw new RuntimeException(
                "Jumlah rincian ({$jumlahRincian}) tidak sama "
                . "dengan total pesanan ({$total})."
            );
        }

        if ($total < 1) {
            throw new RuntimeException(
                'Total pembayaran harus lebih dari nol.'
            );
        }

        // 7. Susun data transaksi.
        $mulai = now();

        $muatan = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],

            'item_details' => $rincian,

            'customer_details' => [
                'first_name' => $pesanan->nama_pemesan,
                'phone'      => $pesanan->telepon,

                'shipping_address' => [
                    'first_name'   => $pesanan->nama_pemesan,
                    'phone'        => $pesanan->telepon,
                    'address'      => $pesanan->alamat,
                    'country_code' => 'IDN',
                ],
            ],

            'callbacks' => [
                'finish' => route('checkout.sukses'),
            ],

            'expiry' => [
                'start_time' => $mulai->format('Y-m-d H:i:s O'),
                'unit'       => 'hour',
                'duration'   => self::BATAS_JAM,
            ],
        ];

        // 8. Kirim permintaan ke Midtrans.
        try {
            $jawaban = Http::withBasicAuth($serverKey, '')
                ->acceptJson()
                ->timeout(15)
                ->post($snapUrl, $muatan);

        } catch (ConnectionException $e) {

            Log::error('Koneksi ke Midtrans gagal', [
                'nomor_pesanan' => $pesanan->nomor_pesanan,
                'galat'         => $e->getMessage(),
            ]);

            throw new RuntimeException(
                'Midtrans tidak dapat dihubungi. Periksa koneksi internet.',
                0,
                $e
            );
        }

        // 9. Periksa jawaban Midtrans.
        if (
            $jawaban->failed()
            || ! $jawaban->json('redirect_url')
        ) {
            Log::error('Midtrans menolak permintaan pembayaran', [
                'nomor_pesanan' => $pesanan->nomor_pesanan,
                'status'        => $jawaban->status(),
                'jawaban'       => $jawaban->json(),
            ]);

            throw new RuntimeException(
                $jawaban->json('error_messages.0')
                ?? "Midtrans menjawab HTTP {$jawaban->status()}."
            );
        }

        // 10. Simpan informasi pembayaran ke pesanan.
        $redirectUrl = $jawaban->json('redirect_url');

        $pesanan->update([
            'midtrans_order_id'     => $orderId,
            'midtrans_redirect_url' => $redirectUrl,
            'batas_bayar'           => $mulai->copy()->addHours(self::BATAS_JAM),
            'status_bayar'          => 'pending',
        ]);

        // 11. Simpan riwayat permintaan pembayaran.
        $pesanan->pembayaran()->create([
            'order_id'           => $orderId,
            'transaction_status' => 'pending',
            'gross_amount'       => $total,
            'signature_sah'      => false,
            'muatan'             => [
                'permintaan' => $muatan,
                'respons'    => $jawaban->json(),
            ],
        ]);

        // 12. Kembalikan URL pembayaran.
        return $redirectUrl;
    }

/**
 * Menerjemahkan status Midtrans menjadi status pembayaran.
 * Aman dijalankan berulang kali dan bersamaan.
 */
public static function terapkan(Pesanan $pesanan, array $muatan): string
{
    return DB::transaction(function () use ($pesanan, $muatan) {

        // 1. Kunci baris pesanan agar tidak diproses bersamaan.
        $pesanan = Pesanan::lockForUpdate()->findOrFail($pesanan->id);

        $lama = $pesanan->status_bayar;

        // 2. Menerjemahkan status dari Midtrans.
        $status = $muatan['transaction_status'] ?? '';
        $fraud = $muatan['fraud_status'] ?? '';

        $baru = match (true) {
            $status === 'settlement' => 'lunas',

            $status === 'capture' && $fraud === 'accept' => 'lunas',

            $status === 'capture' && $fraud === 'challenge' => 'pending',

            $status === 'capture' && $fraud === 'deny' => 'gagal',

            $status === 'pending' => 'pending',

            $status === 'expire' => 'kedaluwarsa',

            in_array($status, ['deny', 'cancel', 'failure'], true) => 'gagal',

            // Status lain tidak diubah.
            default => $lama,
        };

        // 3. Mencegah status pembayaran mundur.

        // Pesanan yang sudah lunas tidak boleh berubah menjadi gagal
        // hanya karena menerima notifikasi dengan status berbeda.
        if ($lama === 'lunas') {
        return 'lunas';
        }

        // Gagal dan kedaluwarsa tidak boleh kembali menjadi pending.
        if (
            in_array($lama, ['gagal', 'kedaluwarsa'], true)
            && $baru === 'pending'
        ) {
            return $lama;
        }

        // 4. Menerapkan status pembayaran.
        $pesanan->status_bayar = $baru;

        if ($baru === 'lunas') {

            $pesanan->metode_bayar = $muatan['payment_type'] ?? null;

            $pesanan->dibayar_pada = $pesanan->dibayar_pada ?? now();

           // Pembayaran berhasil setelah pesanan dibatalkan
// atau stok dikembalikan harus diperiksa admin.
if (
    $pesanan->stok_dikembalikan
    || $pesanan->status === 'dibatalkan'
) {
    Log::critical('Pembayaran lunas pada pesanan yang sudah dibatalkan atau stok dikembalikan', [
        'nomor_pesanan' => $pesanan->nomor_pesanan,
        'status_pesanan' => $pesanan->status,
        'status_bayar_sebelumnya' => $lama,
        'order_id' => $muatan['order_id'] ?? null,
    ]);
}
        }

        if (in_array($baru, ['gagal', 'kedaluwarsa'], true)) {

            // Mengembalikan stok satu kali.
            $pesanan->kembalikanStok();

            // Pesanan yang belum diproses ikut dibatalkan.
            if ($pesanan->status === 'baru') {
                $pesanan->status = 'dibatalkan';
            }

            // Pembalikan pembayaran dari Midtrans.
            if ($lama === 'lunas') {

                $pesanan->dibayar_pada = null;

                Log::warning('Pembayaran dibalik oleh Midtrans', [
                    'nomor_pesanan' => $pesanan->nomor_pesanan,
                ]);
            }
        }

        $pesanan->save();

        return $baru;
    });
}

}
