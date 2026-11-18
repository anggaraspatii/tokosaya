<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $muatan = $request->all();

        // 1. Pastikan data notifikasi lengkap.
        foreach ([
            'order_id',
            'status_code',
            'gross_amount',
            'signature_key',
        ] as $wajib) {
            if (!isset($muatan[$wajib])) {
                Log::warning('Notifikasi Midtrans tidak lengkap');

                return response()->json([
                    'pesan' => 'Muatan tidak lengkap',
                ], 400);
            }
        }

        if (!is_numeric($muatan['gross_amount'])) {
            return response()->json([
                'pesan' => 'Nominal pembayaran tidak valid',
            ], 400);
        }

        // 2. Periksa signature Midtrans.
         $signatureKey = config('midtrans.signature_key');

if (empty($signatureKey)) {
    Log::error('Midtrans Signature Secret Key belum dikonfigurasi');

    return response()->json([
        'pesan' => 'Konfigurasi Signature Key bermasalah',
    ], 500);
}
        $signatureKey = config('midtrans.signature_key');

if (empty($signatureKey)) {
    Log::error('Midtrans Signature Secret Key belum dikonfigurasi');

    return response()->json([
        'pesan' => 'Konfigurasi Signature Key bermasalah',
    ], 500);
}

$hitungan = hash(
    'sha512',
    $muatan['order_id']
    . $muatan['status_code']
    . $muatan['gross_amount']
    . $signatureKey
);

$sah = hash_equals(
    $hitungan,
    (string) $muatan['signature_key']
);

        $hitungan = hash(
    'sha512',
    $muatan['order_id']
    . $muatan['status_code']
    . $muatan['gross_amount']
    . $signatureKey
);

$sah = hash_equals(
    $hitungan,
    (string) $muatan['signature_key']
);

        // 3. Tolak signature palsu sebelum menyimpan data.
        if (!$sah) {
            Log::warning('Tanda tangan Midtrans tidak sah', [
                'order_id' => $muatan['order_id'],
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'pesan' => 'Tanda tangan tidak sah',
            ], 403);
        }

        // 4. Cari riwayat pembayaran berdasarkan order ID.
        $riwayatAwal = Pembayaran::with('pesanan')
            ->where('order_id', $muatan['order_id'])
            ->latest('id')
            ->first();

        $pesanan = $riwayatAwal?->pesanan;

        // Jika tidak ditemukan di riwayat, cari order ID aktif.
        if (!$pesanan) {
            $pesanan = Pesanan::where(
                'midtrans_order_id',
                $muatan['order_id']
            )->first();
        }

        // 5. Jika pesanan tidak dikenal, jangan proses.
        if (!$pesanan) {
            Log::warning('Notifikasi untuk pesanan tidak dikenal', [
                'order_id' => $muatan['order_id'],
            ]);

            return response()->json([
                'pesan' => 'Pesanan tidak ditemukan',
            ], 200);
        }

        // 6. Cocokkan nominal pembayaran.
        $nominalMidtrans = number_format(
            (float) $muatan['gross_amount'],
            2,
            '.',
            ''
        );

        $nominalPesanan = number_format(
            (float) $pesanan->total_harga,
            2,
            '.',
            ''
        );

        if ($nominalMidtrans !== $nominalPesanan) {
            Log::warning('Nominal pembayaran tidak cocok', [
                'order_id' => $muatan['order_id'],
                'nominal_midtrans' => $nominalMidtrans,
                'nominal_pesanan' => $nominalPesanan,
            ]);

            return response()->json([
                'pesan' => 'Nominal pembayaran tidak cocok',
            ], 422);
        }

        // 7. Simpan riwayat notifikasi yang signature-nya sah.
        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'order_id' => $muatan['order_id'],
            'transaction_id' => $muatan['transaction_id'] ?? null,
            'transaction_status' => $muatan['transaction_status'] ?? null,
            'fraud_status' => $muatan['fraud_status'] ?? null,
            'payment_type' => $muatan['payment_type'] ?? null,
            'status_code' => $muatan['status_code'],
            'gross_amount' => $muatan['gross_amount'],
            'signature_sah' => true,
            'muatan' => $muatan,
        ]);

        // 8. Periksa apakah notifikasi berasal dari order ID lama.
        $orderIdLama = $pesanan->midtrans_order_id
            !== $muatan['order_id'];

        if ($orderIdLama) {
            $status = $muatan['transaction_status'] ?? '';
            $fraud = $muatan['fraud_status'] ?? '';

            // Order lama hanya boleh melunasi jika benar-benar berhasil.
            $bolehMelunasi = $status === 'settlement'
                || ($status === 'capture' && $fraud === 'accept');

            if (!$bolehMelunasi) {
                Log::info('Notifikasi order ID lama diabaikan', [
                    'order_id' => $muatan['order_id'],
                    'transaction_status' => $status,
                ]);

                return response()->json([
                    'pesan' => 'Diabaikan: order ID lama',
                ], 200);
            }
        }

        // 9. Terapkan status pembayaran.
        $hasil = Midtrans::terapkan($pesanan, $muatan);

        return response()->json([
            'pesan' => 'Notifikasi diterima',
            'status_bayar' => $hasil,
        ], 200);
    }
}
