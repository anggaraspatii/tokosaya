<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')
            ->latest()
            ->get();

        return view('back_office.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('back_office.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'nama' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'stok' => ['required', 'integer', 'min:0'],
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($request->nama);

        // Jika slug sudah ada, tambahkan angka
        $slugDasar = $validated['slug'];
        $nomor = 1;

        while (Produk::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $slugDasar . '-' . $nomor;
            $nomor++;
        }

        $validated['gambar'] = $request->file('gambar')
            ->store('produk', 'public');

        Produk::create($validated);

        return redirect()
            ->route('back-office.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        return view('back_office.produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('back_office.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'nama' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'stok' => ['required', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);

        $slugDasar = Str::slug($request->nama);
        $slugBaru = $slugDasar;
        $nomor = 1;

        while (
            Produk::where('slug', $slugBaru)
                ->where('id', '!=', $produk->id)
                ->exists()
        ) {
            $slugBaru = $slugDasar . '-' . $nomor;
            $nomor++;
        }

        $validated['slug'] = $slugBaru;

        if ($request->hasFile('gambar')) {

            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $validated['gambar'] = $request->file('gambar')
                ->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()
            ->route('back-office.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()
            ->route('back-office.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
