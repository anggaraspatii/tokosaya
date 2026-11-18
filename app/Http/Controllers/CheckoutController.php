<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\Keranjang;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index(Keranjang $keranjang)
    {
        $items = $keranjang->semua();

        if (count($items) === 0) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        $totalHarga = $keranjang->totalHarga();

        return view('user_front.checkout.index', compact(
            'items',
            'totalHarga'
        ));
    }

    public function store(Request $request, Keranjang $keranjang)
    {
        $validated = $request->validate([
            'nama_pemesan' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:2000'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $items = $keranjang->semua();

        if (count($items) === 0) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        try {
            $pesanan = DB::transaction(function () use ($items, $validated) {
                $dataProduk = [];
                $totalHarga = 0;

                foreach ($items as $id => $item) {
                    $produk = Produk::whereKey($id)
                        ->lockForUpdate()
                        ->first();

                    $jumlah = (int) $item['jumlah'];

                    if (
                        !$produk ||
                        !$produk->status ||
                        $jumlah < 1 ||
                        $produk->stok < $jumlah
                    ) {
                        throw ValidationException::withMessages([
                            'keranjang' => 'Stok produk tidak mencukupi.',
                        ]);
                    }

                    $harga = (float) $produk->harga;
                    $subtotal = $harga * $jumlah;

                    $dataProduk[] = [
                        'produk' => $produk,
                        'jumlah' => $jumlah,
                        'harga' => $harga,
                        'subtotal' => $subtotal,
                    ];

                    $totalHarga += $subtotal;
                }

                $pesanan = Pesanan::create([
                    'nomor_pesanan' => 'ORD-' . now()->format('YmdHis')
                        . '-' . Str::upper(Str::random(5)),
                    'nama_pemesan' => $validated['nama_pemesan'],
                    'telepon' => $validated['telepon'],
                    'alamat' => $validated['alamat'],
                    'catatan' => $validated['catatan'] ?? null,
                    'total_harga' => $totalHarga,
                    'status' => 'baru',
                    'status_bayar' => 'belum_bayar',
                ]);

                foreach ($dataProduk as $data) {
                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $data['produk']->id,
                        'nama_produk' => $data['produk']->nama,
                        'harga' => $data['harga'],
                        'jumlah' => $data['jumlah'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    $data['produk']->decrement('stok', $data['jumlah']);
                }

                return $pesanan;
            });

            // Buat halaman pembayaran Midtrans.
            $urlPembayaran = Midtrans::mintaHalamanBayar($pesanan);

            // Simpan ID pesanan untuk halaman sukses.
            $request->session()->put('pesanan_sukses_id', $pesanan->id);

            // Kosongkan keranjang setelah pembayaran berhasil dibuat.
            $keranjang->kosongkan();

            // Arahkan pembeli ke halaman pembayaran Midtrans.
            return redirect()->away($urlPembayaran);

        } catch (RuntimeException $e) {
            report($e);

            return redirect()
                ->route('checkout.index')
                ->with('error', 'Pembayaran gagal dibuat. Silakan coba lagi.');
        }
    }

    public function sukses(Request $request)
    {
        $pesananId = $request->session()->get('pesanan_sukses_id');

        if (!$pesananId) {
            return redirect()
                ->route('home')
                ->with('error', 'Data pesanan tidak ditemukan.');
        }

        $pesanan = Pesanan::with([
            'detailPesanans',
            'pembayaran',
        ])->find($pesananId);

        if (!$pesanan) {
            $request->session()->forget('pesanan_sukses_id');

            return redirect()->route('home');
        }

        return view('user_front.checkout.sukses', compact('pesanan'));
    }
}
