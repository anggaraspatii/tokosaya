
@extends('user_front.layouts.app')

@section('judul', $item->nama)

@section('konten')
<div class="container mx-auto px-4 py-10">

    <a href="{{ route('produk.index') }}"
       class="inline-block text-blue-600 hover:underline mb-6">
        &larr; {{ __('web.back_to') }} {{ __('web.product_list') }}
    </a>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">



{{-- Gambar produk --}}
<div class="w-full h-72 bg-gray-50 flex items-center justify-center p-2">
    @if ($item->gambar)
        <img
            src="{{ asset('storage/' . $item->gambar) }}"
            alt="{{ $item->nama }}"
            style="width: 80%; height: 80%; object-fit: contain; object-position: center;"
            loading="lazy"
        >
    @else
        <div class="text-gray-500">
            {{ __('web.image_unavailable') }}
        </div>
    @endif
</div>


            {{-- Informasi produk --}}
            <div>
                <h1 class="text-3xl font-bold mb-4">
                    {{ $item->nama }}
                </h1>

                <p class="text-gray-600 mb-4">
                    {{ __('web.category') }}:
                    {{ $item->kategori->nama ?? __('web.no_category') }}
                </p>

                <p class="text-2xl font-bold text-blue-600 mb-6">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </p>

                <h2 class="text-lg font-semibold mb-2">
                    {{ __('web.description') }}
                </h2>

                <p class="text-gray-700 mb-6 whitespace-pre-line">
                    {{ $item->deskripsi }}
                </p>


{{-- Informasi stok --}}
<div class="mb-6">
    <p class="text-gray-600 mb-2">
        {{ __('web.stock_available') }}:
        <span class="font-semibold {{ $item->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $item->stok }}
        </span>
    </p>

    @if ($item->stok > 0)
        <form action="{{ route('keranjang.tambah') }}" method="POST">
            @csrf

            <input type="hidden" name="produk_id" value="{{ $item->id }}">

            <label for="jumlah" class="block font-semibold mb-2">
                Jumlah
            </label>

            <input
                type="number"
                id="jumlah"
                name="jumlah"
                value="1"
                min="1"
                max="{{ $item->stok }}"
                required
                class="w-24 border border-gray-300 rounded px-3 py-2 mb-4"
            >

            <button
                type="submit"
                class="block bg-primary text-white px-5 py-3 rounded hover:opacity-90"
            >
                Masuk Keranjang
            </button>
        </form>
    @else
        <p class="text-red-600 font-semibold mb-4">
            Stok Habis
        </p>
    @endif
</div>

<a
    href="{{ route('produk.index') }}"
    class="inline-block border border-primary text-primary px-5 py-3 rounded hover:bg-primary hover:text-white"
>
    {{ __('web.other_products') }}
</a>

                <a href="{{ route('produk.index') }}"
                   class="inline-block bg-primary text-white px-5 py-3 rounded hover:opacity-90">
                    {{ __('web.other_products') }}
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
