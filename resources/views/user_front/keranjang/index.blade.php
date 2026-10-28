@extends('user_front.layouts.app')

@section('judul', __('web.cart_title'))

@section('konten')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-8">
    {{ __('web.cart_title') }}
    </h1>
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
        {{ $errors->first('jumlah') }}
    </div>
    @endif

    @if (count($items) > 0)

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-fixed text-left">



<thead class="bg-gray-100">
    <tr>
        <th class="p-4 w-[38%]">{{ __('web.cart_product') }}</th>
        <th class="p-4 w-[16%]">{{ __('web.cart_price') }}</th>
        <th class="p-4 w-[20%]">{{ __('web.cart_quantity') }}</th>
        <th class="p-4 w-[16%]">{{ __('web.cart_subtotal') }}</th>
        <th class="p-4 w-[10%]">{{ __('web.cart_action') }}</th>
    </tr>
</thead>

                    <tbody class="divide-y">
                        @foreach ($items as $id => $item)
                            <tr>

                        <td class="p-4">
                        <div class="flex items-center gap-4">
                        @if ($item['produk']->gambar)
                        <img
                        src="{{ asset('storage/' . $item['produk']->gambar) }}"
                        alt="{{ $item['produk']->nama }}"
                         width="80"
                        height="80"
                        style="width: 80px; height: 80px; min-width: 80px; object-fit: contain;"
                        class="rounded border">
                    @endif

        <span style="margin-left: 12px; overflow-wrap: anywhere;" class="font-semibold">
            {{ $item['produk']->nama }}
        </span>
    </div>
</td>

                        <td class="p-4 whitespace-nowrap">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </td>

                    <td class="p-4">

<form
    action="{{ route('keranjang.update', $id) }}"
    method="POST"
    class="flex flex-col items-start gap-2"
>
    @csrf
    @method('PATCH')

    <input
        type="number"
        name="jumlah"
        value="{{ $item['jumlah'] }}"
        class="w-16 border rounded-lg px-2 py-2"
        required
    >


<button
    type="submit"
    style="background-color: #2563eb; color: white; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer; display: inline-block;"
>
    {{ __('web.cart_update') }}
</button>

</form>
                                </td>

                                <td class="p-4 font-semibold whitespace-nowrap">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>

                                <td class="p-4">
                                    <form
                                        action="{{ route('keranjang.hapus', $id) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ app()->getLocale() === 'en' ? 'Remove this product?' : 'Yakin ingin menghapus produk ini?' }}')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            style="background-color: #eb2525; color: white; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; display: inline-block;">
                                            {{ __('web.cart_remove') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-gray-600 mb-2">
         {{ __('web.cart_total_items') }}:
        <strong>{{ $jumlahItem }}</strong>
        </p>

        <p class="text-2xl font-bold mb-6">
        {{ __('web.cart_total_price') }}:
        Rp {{ number_format($totalHarga, 0, ',', '.') }}
        </p>
        <a
             href="{{ route('checkout.index') }}"
            style="display: inline-block; background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 8px; margin-bottom: 24px;"
        >
         {{ app()->getLocale() === 'en' ? 'Proceed to Checkout' : 'Lanjut ke Checkout' }}
        </a>
            <form
                {{ __('web.cart_clear') }}
                method="POST"
                onsubmit="return confirm('{{ __('web.cart_confirm_clear') }}')"
                @method('DELETE')

                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg"
                >
                    {{ app()->getLocale() === 'en' ? 'Empty Cart' : 'Kosongkan Keranjang' }}
                </button>
            </form>
        </div>

    @else

        <div class="bg-white rounded-xl shadow p-10 text-center">
            <div class="text-6xl mb-4">🛒</div>

            <h2 class="text-2xl font-bold mb-3">
            {{ __('web.cart_empty') }}
            </h2>

            <p class="text-gray-600 mb-6">
            {{ __('web.cart_empty_description') }}
            </p>
            <a
                href="{{ route('produk.index') }}"
                class="inline-block bg-primary text-white px-6 py-3 rounded-lg hover:opacity-90">
                {{ __('web.cart_start_shopping') }}
            </a>
        </div>

    @endif

</div>
@endsection
