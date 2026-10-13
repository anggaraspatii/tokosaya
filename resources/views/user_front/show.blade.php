
@extends('user_front.layouts.app')

@section('konten')
<div class="max-w-5xl mx-auto px-6 py-10">

    <a href="{{ route('produk.index') }}"
       class="inline-block text-blue-600 hover:underline mb-6">
        &larr; Kembali ke Katalog
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-xl shadow p-6">

        <div>
            @if ($item->gambar)
                <img
                    src="{{ asset('storage/' . $item->gambar) }}"
                    alt="{{ $item->nama }}"
                    class="w-full h-96 object-cover rounded-lg"
                >
            @else
                <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                    Gambar belum tersedia
                </div>
            @endif
        </div>

        <div>
            <p class="text-blue-600 font-medium mb-2">
                {{ $item->kategori->nama ?? 'Tanpa kategori' }}
            </p>

            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                {{ $item->nama }}
            </h1>

            <p class="text-2xl font-bold text-pink-600 mb-6">
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </p>

            <h2 class="font-semibold text-lg mb-2">Deskripsi Produk</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                {{ $item->deskripsi }}
            </p>

            <p class="text-gray-700 mb-6">
                Stok tersedia:
                <span class="font-semibold">{{ $item->stok }}</span>
            </p>

            <a href="{{ route('produk.index') }}"
               class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-5 py-3 rounded-lg">
                Lihat Produk Lain
            </a>
        </div>

    </div>
</div>
@endsection