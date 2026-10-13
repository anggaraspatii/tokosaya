<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('produks')->latest()->get();

        return view('back_office.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('back_office.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
        ]);

        Kategori::create([
            'nama' => $request->nama,
        ]);

        return redirect()
            ->route('back-office.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('back_office.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()
            ->route('back-office.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produks()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $kategori->delete();

        return redirect()
            ->route('back-office.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
