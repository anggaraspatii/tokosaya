
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

        @if (session('error'))
            <div class="bg-red-100 text-red-700 rounded-lg p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

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
            <p class="text-xl font-bold mb-4">
                Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
            </p>

            <p class="text-gray-600 mb-1">Status pembayaran</p>

            @php
                $statusBayar = [
                    'belum_bayar' => ['Belum Dibayar', 'bg-gray-200 text-gray-700'],
                    'pending' => ['Menunggu Pembayaran', 'bg-yellow-100 text-yellow-800'],
                    'lunas' => ['Lunas', 'bg-green-100 text-green-800'],
                    'gagal' => ['Gagal', 'bg-red-100 text-red-700'],
                    'kedaluwarsa' => ['Kedaluwarsa', 'bg-gray-200 text-gray-700'],
                ];

                $status = $statusBayar[$pesanan->status_bayar]
                    ?? ['Tidak Diketahui', 'bg-gray-200 text-gray-700'];
            @endphp

            <span class="inline-block px-3 py-2 rounded-lg font-semibold {{ $status[1] }}">
                {{ $status[0] }}
            </span>
        </div>

        @if (
            in_array($pesanan->status_bayar, ['belum_bayar', 'pending'])
            && $pesanan->midtrans_redirect_url
            && $pesanan->bisaDibayar()
        )
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5 mb-6">
                <p class="font-semibold text-yellow-800 mb-2">
                    Pesanan menunggu pembayaran
                </p>

                <p class="text-sm text-gray-600 mb-4">
                    Silakan lanjutkan pembayaran melalui Midtrans.
                    Jika sebelumnya sudah memilih metode pembayaran,
                    halaman ini dapat dibuka kembali untuk melihat instruksinya.
                </p>

                <a
                    href="{{ $pesanan->midtrans_redirect_url }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg"
                >
                    Bayar Sekarang
                </a>

                @if ($pesanan->batas_bayar)
                    <p class="text-sm text-gray-500 mt-3">
                        Batas pembayaran:
                        {{ $pesanan->batas_bayar->format('d-m-Y H:i') }}
                    </p>
                @endif
            </div>
        @elseif ($pesanan->status_bayar === 'lunas')
            <div class="bg-green-50 text-green-700 rounded-lg p-4 mb-6">
                Pembayaran Anda sudah diterima. Terima kasih!
            </div>
        @endif

        <h2 class="text-lg font-bold text-left mb-3">
            Detail pesanan
        </h2>

        <div class="divide-y text-left mb-8">
            @foreach ($pesanan->detailPesanans as $detail)
                <div class="py-3 flex justify-between gap-4">
                    <div>
                        <p class="font-semibold">
                            {{ $detail->nama_produk }}
                        </p>

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
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg"
        >
            Lanjut Belanja
        </a>

    </div>
</div>
@endsection
