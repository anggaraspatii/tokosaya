@extends('user_front.layouts.app')

@section('judul', 'Pesanan Berhasil')

@section('konten')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8 text-center">
        <div class="text-6xl mb-5">✅</div>

        <h1 class="text-3xl font-bold text-green-700 mb-4">
            Pesanan Berhasil!
        </h1>

        <p class="text-gray-600 mb-6">
            Terima kasih telah berbelanja di toko kami.
            Pesanan Anda telah berhasil disimpan.
        </p>

        <div class="bg-gray-100 rounded-lg p-5 mb-6 text-left">
            <p class="text-gray-600 mb-1">Nomor pesanan</p>
            <p class="text-xl font-bold mb-4">
                {{ $pesanan->nomor_pesanan }}
            </p>

            <p class="text-gray-600 mb-1">Nama pemesan</p>
            <p class="font-semibold mb-4">
                {{ $pesanan->nama_pemesan }}
            </p>

            <p class="text-gray-600 mb-1">Total pembayaran</p>
            <p class="text-xl font-bold">
                Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
            </p>
        </div>

        <h2 class="text-lg font-bold text-left mb-3">Detail pesanan</h2>

        <div class="divide-y text-left mb-8">
            @foreach ($pesanan->detailPesanans as $detail)
                <div class="py-3 flex justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $detail->nama_produk }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $detail->jumlah }} ×
                            Rp {{ number_format((float) $detail->harga, 0, ',', '.') }}
                        </p>
                    </div>

                    <p class="font-semibold whitespace-nowrap">
                        Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>

    <a
    href="{{ route('produk.index') }}"
    class="inline-block bg-blue-600 hover:bg-blue-700 text-black font-semibold px-10 py-6 rounded-lg"
    >
    Lanjut Belanja
    </a>
    </div>
</div>
@endsection
