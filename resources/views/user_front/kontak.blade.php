
@extends('user_front.layouts.app')

@section('judul', __('web.contact'))

@section('konten')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-4">
        {{ __('web.contact') }}
    </h1>

    <p class="mb-8">
        {{ __('web.contact_intro') }}
    </p>

    <div class="bg-white p-8 rounded-lg shadow">

        <div class="mb-4">
            <h2 class="font-bold text-lg">
                {{ __('web.address') }}
            </h2>
            <p>
                Jl. Waturenggong nomor 14, Gelgel, Klungkung, Klungkung, Bali
            </p>
        </div>

        <div class="mb-4">
            <h2 class="font-bold text-lg">
                {{ __('web.email') }}
            </h2>
            <p>
                anggaraspatii@gmail.com
            </p>
        </div>

        <div class="mb-4">
            <h2 class="font-bold text-lg">
                {{ __('web.phone') }}
            </h2>
            <p>
                0896-9733-0377
            </p>
        </div>

        <div>
            <h2 class="font-bold text-lg">
                {{ __('web.operating_hours') }}
            </h2>
            <p>
                {{ __('web.schedule') }}
            </p>
        </div>

    </div>
</div>
@endsection
