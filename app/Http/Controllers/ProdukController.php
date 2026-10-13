<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('kategori')
            ->where('status', true)
            ->latest()
            ->get();

        return view('user_front.produk.index', compact('produk'));
    }

    public function show($slug)
    {
        $item = Produk::with('kategori')
            ->where('status', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('user_front.produk.show', compact('item'));
    }
}
