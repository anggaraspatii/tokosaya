@extends('user_front.layouts.app')

@section('judul', 'Produk')

@section('konten')

<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-8">
        Daftar Produk
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach ($produk as $item)

            <div class="bg-white p-6 rounded-lg shadow">

                <h2 class="text-xl font-bold mb-2">
                    {{ $item['nama'] }}
                </h2>

                <p class="text-gray-600 mb-2">
                    Kategori: {{ $item['kategori'] }}
                </p>

                <p class="text-lg font-bold mb-3">
                    Rp {{ number_format($item['harga'], 0, ',', '.') }}
                </p>

                <p class="mb-4">
                    {{ $item['deskripsi'] }}
                </p>

                <a
                    <a
                  href="{{ route('produk.show', $item['id']) }}"
                  class="bg-primary text-white px-4 py-2 rounded"
                >
                  Lihat Detail
</a>
                </a>

            </div>

        @endforeach

    </div>

</div>

@endsection
