<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index(Keranjang $keranjang)
    {
        $items = $keranjang->semua();

        if (count($items) === 0) {
            return redirect()
                ->route('keranjang.index')
                ->with('success', 'Keranjang belanja masih kosong.');
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
                ->with('success', 'Keranjang belanja masih kosong.');
        }

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
                        'keranjang' => 'Stok salah satu produk tidak mencukupi. Periksa kembali keranjang Anda.',
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
                'nomor_pesanan' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
                'nama_pemesan' => $validated['nama_pemesan'],
                'telepon' => $validated['telepon'],
                'alamat' => $validated['alamat'],
                'catatan' => $validated['catatan'] ?? null,
                'total_harga' => $totalHarga,
                'status' => 'baru',
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

        $keranjang->kosongkan();

        // Simpan ID pesanan di session untuk membatasi akses halaman sukses.
        $request->session()->put('pesanan_sukses_id', $pesanan->id);

        return redirect()->route('checkout.sukses');
    }

    public function sukses(Request $request)
    {
        $pesananId = $request->session()->get('pesanan_sukses_id');

        if (!$pesananId) {
            abort(403);
        }

        $pesanan = Pesanan::with('detailPesanans')
            ->find($pesananId);

        if (!$pesanan) {
            $request->session()->forget('pesanan_sukses_id');
            abort(403);
        }

        return view('user_front.checkout.sukses', compact('pesanan'));
    }
}
