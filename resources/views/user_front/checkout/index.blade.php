@extends('user_front.layouts.app')

@section('judul', 'Checkout')

@section('konten')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            <p class="font-bold mb-2">Periksa kembali data Anda:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-6">Informasi Pemesan</h2>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="nama_pemesan" class="block font-semibold mb-2">
                        Nama lengkap
                    </label>
                    <input
                        type="text"
                        id="nama_pemesan"
                        name="nama_pemesan"
                        value="{{ old('nama_pemesan') }}"
                        class="w-full border rounded-lg px-4 py-3"
                        maxlength="255"
                        required
                    >
                    @error('nama_pemesan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="telepon" class="block font-semibold mb-2">
                        Nomor telepon
                    </label>
                    <input
                        type="tel"
                        id="telepon"
                        name="telepon"
                        value="{{ old('telepon') }}"
                        class="w-full border rounded-lg px-4 py-3"
                        maxlength="30"
                        required
                    >
                    @error('telepon')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="alamat" class="block font-semibold mb-2">
                        Alamat lengkap
                    </label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="4"
                        class="w-full border rounded-lg px-4 py-3"
                        maxlength="2000"
                        required
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="catatan" class="block font-semibold mb-2">
                        Catatan (opsional)
                    </label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="3"
                        class="w-full border rounded-lg px-4 py-3"
                        maxlength="2000"
                    >{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

<button
    type="submit"
    style="display: block; width: 100%; background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer;"
>
    Buat Pesanan
</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow p-6 h-fit">
            <h2 class="text-xl font-bold mb-6">Ringkasan Belanja</h2>

            <div class="divide-y">
                @foreach ($items as $item)
                    <div class="py-4 flex justify-between gap-4">
                        <div>
                            <p class="font-semibold">{{ $item['produk']->nama }}</p>
                            <p class="text-gray-600 text-sm">
                                {{ $item['jumlah'] }} ×
                                Rp {{ number_format((float) $item['harga'], 0, ',', '.') }}
                            </p>
                        </div>
                        <p class="font-semibold whitespace-nowrap">
                            Rp {{ number_format((float) $item['subtotal'], 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="border-t mt-4 pt-4 flex justify-between gap-4 text-xl font-bold">
                <span>Total</span>
                <span class="whitespace-nowrap">
                    Rp {{ number_format((float) $totalHarga, 0, ',', '.') }}
                </span>
            </div>

            <a
                href="{{ route('keranjang.index') }}"
                class="block text-center mt-6 text-blue-600 hover:underline"
            >
                Kembali ke keranjang
            </a>
        </div>
    </div>
</div>
@endsection
