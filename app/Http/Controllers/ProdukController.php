<?php

namespace App\Http\Controllers;

use App\Data\ProdukDummy;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = ProdukDummy::semua();

        return view('user_front.produk.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = ProdukDummy::semua();

        $item = collect($produk)->firstWhere('id', $id);

        if (!$item) {
            abort(404);
        }

        return view('user_front.produk.show', compact('item'));
    }
}
