@extends('user_front.layouts.app')

@section('judul', $item['nama'])

@section('konten')

<div class="container mx-auto px-4 py-10">

    <a href="{{ route('produk.index') }}"
       class="inline-block mb-6">
        ← Kembali ke Produk
    </a>

    <div class="bg-white p-8 rounded-lg shadow">

        <h1 class="text-3xl font-bold mb-4">
            {{ $item['nama'] }}
        </h1>


<p class="text-blue-600 font-medium mb-2">
    {{ $item->kategori->nama ?? 'Tanpa kategori' }}
</p>


        <p class="text-2xl font-bold mb-4">
            Rp {{ number_format($item['harga'], 0, ',', '.') }}
        </p>

        <p class="mb-6">
            {{ $item['deskripsi'] }}
        </p>

    </div>

</div>

@endsection
