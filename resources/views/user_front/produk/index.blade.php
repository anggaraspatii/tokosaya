
@extends('user_front.layouts.app')

@section('judul', __('web.product_list'))

@section('konten')

<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-8">
        {{ __('web.product_list') }}
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse ($produk as $item)

            <div class="bg-white rounded-lg shadow overflow-hidden">




{{-- Gambar produk --}}
<div class="w-full h-80 md:h-[450px] bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center p-4">
    @if ($item->gambar)
        <img
            src="{{ asset('storage/' . $item->gambar) }}"
            alt="{{ $item->nama }}"
            class="block w-full h-full object-contain object-center"
            loading="lazy"
        >
    @else
        <div class="text-gray-500">
            {{ __('web.image_unavailable') }}
        </div>
    @endif
</div>



                {{-- Informasi produk --}}
                <div class="p-6">

                    <h2 class="text-xl font-bold mb-2">
                        {{ $item->nama }}
                    </h2>

                    <p class="text-gray-600 mb-2">
                        {{ __('web.category') }}:
                        {{ $item->kategori->nama ?? __('web.no_category') }}
                    </p>

                    <p class="text-lg font-bold mb-3">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <p class="text-gray-700 mb-4">
                        {{ $item->deskripsi }}
                    </p>

                    <a
                        href="{{ route('produk.show', $item->slug) }}"
                        class="inline-block bg-primary text-white px-4 py-2 rounded hover:opacity-90"
                    >
                        {{ __('web.product_detail') }}
                    </a>

                </div>
            </div>

        @empty

            <p class="text-gray-600">
                {{ __('web.no_products') }}
            </p>

        @endforelse

    </div>

</div>

@endsection
