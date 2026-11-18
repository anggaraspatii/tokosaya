<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use RuntimeException;

class MidtransController extends Controller
{
    /**
     * Menampilkan halaman menunggu pembayaran.
     */
    public function show(Request $request, $id)
    {
        $pesanan = Pesanan::with('detailPesanans')->findOrFail($id);

        // Pastikan pesanan berasal dari sesi pembeli.
        if ((int) $request->session()->get('pesanan_sukses_id') !== (int) $pesanan->id) {
            abort(403);
        }

        return view('user_front.checkout.menunggu', compact('pesanan'));
    }

    /**
     * Membuka atau membuat ulang halaman pembayaran.
     */
    public function bayar(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Batasi akses pembayaran berdasarkan sesi.
        if ((int) $request->session()->get('pesanan_sukses_id') !== (int) $pesanan->id) {
            abort(403);
        }

        if ($pesanan->sudahLunas()) {
            return redirect()
                ->route('pembayaran.show', $pesanan->id)
                ->with('success', 'Pesanan ini sudah dibayar.');
        }

        try {
            $url = Midtrans::mintaHalamanBayar($pesanan);

            return redirect()->away($url);

        } catch (RuntimeException $e) {
            report($e);

            return redirect()
                ->route('pembayaran.show', $pesanan->id)
                ->with('error', 'Halaman pembayaran gagal dibuka. Silakan coba kembali.');
        }
    }
}
