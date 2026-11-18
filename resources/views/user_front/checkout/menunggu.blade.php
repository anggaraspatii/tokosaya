
@extends('user_front.layouts.app')

@section('judul', 'Menunggu Pembayaran')

@section('konten')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow p-8 text-center">

        <div class="text-5xl mb-4">⏳</div>

        <h1 class="text-2xl font-bold mb-4">
            Menunggu Pembayaran
        </h1>

        <p class="text-gray-600 mb-6">
            Silakan selesaikan pembayaran pesanan Anda melalui Midtrans.
        </p>

        <div class="bg-gray-100 rounded-lg p-5 mb-6 text-left">
            <p class="text-gray-500">Nomor Pesanan</p>
            <p class="font-bold mb-4">
                {{ $pesanan->nomor_pesanan }}
            </p>

            <p class="text-gray-500">Total Pembayaran</p>
            <p class="text-xl font-bold">
                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
            </p>

            <p class="text-gray-500 mt-4">Status Pembayaran</p>
            <p class="font-bold text-yellow-600">
                {{ $pesanan->labelBayar()[0] }}
            </p>
        </div>

        @if(session('error'))
            <p class="text-red-600 mb-4">
                {{ session('error') }}
            </p>
        @endif

        @if(session('success'))
            <p class="text-green-600 mb-4">
                {{ session('success') }}
            </p>
        @endif

        @if($pesanan->bisaDibayar())
            <form action="{{ route('pembayaran.bayar', $pesanan->id) }}"
                  method="POST">
                @csrf

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg">
                    Bayar Sekarang / Buka Lagi Halaman Pembayaran
                </button>
            </form>
        @endif

    </div>
</div>
@endsection
