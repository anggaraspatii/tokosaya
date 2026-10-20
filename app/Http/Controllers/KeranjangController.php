<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index(Keranjang $keranjang)
    {
        $items = $keranjang->semua();
        $totalHarga = $keranjang->totalHarga();
        $jumlahItem = $keranjang->jumlahItem();

        return view('user_front.keranjang.index', compact(
            'items',
            'totalHarga',
            'jumlahItem'
        ));
    }

    public function tambah(Request $request, Keranjang $keranjang)
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $keranjang->tambah(
            (int) $validated['produk_id'],
            (int) $validated['jumlah']
        );

        return redirect()
            ->route('keranjang.index')
            ->with('success', __('web.cart_added'));
    }

    public function update(Request $request, int $produkId, Keranjang $keranjang)
    {
        $validated = $request->validate([
    'jumlah' => [
        'required',
        'integer',
        'min:1',
    ],
], [
    'jumlah.min' => __('web.cart_min_quantity'),
]);

        $keranjang->ubahJumlah(
            $produkId,
            (int) $validated['jumlah']
        );

        return redirect()
            ->route('keranjang.index')
            ->with('success', __('web.cart_updated'));
    }

    public function hapus(int $produkId, Keranjang $keranjang)
    {
        $keranjang->hapus($produkId);

        return redirect()
            ->route('keranjang.index')
            ->with('success', __('web.cart_removed'));
    }

    public function kosongkan(Keranjang $keranjang)
    {
        $keranjang->kosongkan();

        return redirect()
            ->route('keranjang.index')
            ->with('success', __('web.cart_cleared'));
    }
}
