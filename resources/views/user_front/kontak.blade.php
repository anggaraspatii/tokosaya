@extends('user_front.layouts.app')

@section('judul', 'Kontak Kami')

@section('konten')

<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-4">
        Kontak Kami
    </h1>

    <p class="mb-8">
        Jika kamu memiliki pertanyaan atau membutuhkan informasi,
        silakan hubungi kami melalui kontak di bawah ini.
    </p>

    <div class="bg-white p-8 rounded-lg shadow">

        <div class="mb-4">
            <h2 class="font-bold text-lg">
                Alamat
            </h2>
            <p>
                Jl. Waturenggong nomor 14, Gelgel, Klungkung, Klungkung, Bali
            </p>
        </div>

        <div class="mb-4">
            <h2 class="font-bold text-lg">
                Email
            </h2>
            <p>
                anggaraspatii@gmail.com
            </p>
        </div>

        <div class="mb-4">
            <h2 class="font-bold text-lg">
                Telepon
            </h2>
            <p>
                0896-9733-0377
            </p>
        </div>

        <div>
            <h2 class="font-bold text-lg">
                Jam Operasional
            </h2>
            <p>
                Senin - Sabtu, 08.00 - 17.00
            </p>
        </div>

    </div>

</div>

@endsection
