
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

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- FORM INFORMASI PEMESAN --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-6">Informasi Pemesan</h2>

            <form
                action="{{ route('checkout.store') }}"
                method="POST"
                id="form-checkout"
            >
                @csrf

                {{-- Nama Pemesan --}}
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

                {{-- Nomor Telepon --}}
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

                {{-- Alamat Lengkap --}}
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

                {{-- PILIH PROVINSI --}}
                <div class="mb-5">
                    <label for="provinsi_id" class="block font-semibold mb-2">
                        {{ __('web.provinsi') }}
                    </label>

                    <select
                        name="provinsi_id"
                        id="provinsi_id"
                        class="w-full border rounded-lg px-4 py-3"
                        required
                    >
                        <option value="">
                            {{ __('web.pilih_provinsi') }}
                        </option>

                        @foreach ($daftarProvinsi as $provinsi)
                            <option
                                value="{{ $provinsi->id }}"
                                @selected(old('provinsi_id') == $provinsi->id)
                            >
                                {{ $provinsi->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('provinsi_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PILIH KOTA DAN KECAMATAN --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="mb-5">
                        <label for="kota_id" class="block font-semibold mb-2">
                            {{ __('web.kota') }}
                        </label>

                        <select
                            name="kota_id"
                            id="kota_id"
                            data-lama="{{ old('kota_id') }}"
                            class="w-full border rounded-lg px-4 py-3"
                            disabled
                            required
                        >
                            <option value="">
                                {{ __('web.pilih_kota') }}
                            </option>
                        </select>

                        @error('kota_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="kecamatan_id" class="block font-semibold mb-2">
                            {{ __('web.kecamatan') }}
                        </label>

                        <select
                            name="kecamatan_id"
                            id="kecamatan_id"
                            data-lama="{{ old('kecamatan_id') }}"
                            class="w-full border rounded-lg px-4 py-3"
                            disabled
                            required
                        >
                            <option value="">
                                {{ __('web.pilih_kecamatan') }}
                            </option>
                        </select>

                        @error('kecamatan_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- KODE POS --}}
                <div class="mb-5">
                    <label for="kode_pos" class="block font-semibold mb-2">
                        {{ __('web.kode_pos') }}
                    </label>

                    <input
                        type="text"
                        id="kode_pos"
                        name="kode_pos"
                        value="{{ old('kode_pos') }}"
                        class="w-full border rounded-lg px-4 py-3"
                        maxlength="10"
                    >

                    @error('kode_pos')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PILIHAN PENGIRIMAN --}}
                <h2 class="text-xl font-bold mb-4">
                    {{ __('web.pengiriman') }}
                </h2>

                <div
                    id="daftar-layanan"
                    data-lama="{{ old('layanan') }}"
                    class="mb-3 text-gray-600"
                >
                    {{ __('web.pilih_alamat_dulu') }}
                </div>

                @error('layanan')
                    <p class="text-red-600 text-sm mt-1 mb-4">
                        {{ $message }}
                    </p>
                @enderror

                <p class="text-sm text-gray-600 mb-6">
                    {{ __('web.berat_total') }}:
                    <strong>
                        {{ number_format($totalBerat, 0, ',', '.') }} gram
                    </strong>
                </p>

                {{-- CATATAN --}}
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

                {{-- TOMBOL PESANAN --}}
                <button
                    type="submit"
                    style="display: block; width: 100%; background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer;"
                >
                    Buat Pesanan
                </button>

            </form>
        </div>

        {{-- RINGKASAN BELANJA --}}
        <div class="bg-white rounded-xl shadow p-6 h-fit">

            <h2 class="text-xl font-bold mb-6">
                Ringkasan Belanja
            </h2>

            <div class="divide-y">

                @foreach ($items as $item)
                    <div class="py-4 flex justify-between gap-4">

                        <div>
                            <p class="font-semibold">
                                {{ $item['produk']->nama }}
                            </p>

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

            {{-- SUBTOTAL --}}
            <div class="border-t mt-4 pt-4 flex justify-between gap-4">
                <span class="font-semibold">Subtotal Produk</span>

                <span class="font-semibold whitespace-nowrap">
                    Rp {{ number_format((float) $totalHarga, 0, ',', '.') }}
                </span>
            </div>

            {{-- ONGKOS KIRIM --}}
            <div class="flex justify-between mt-4 pb-4 border-b gap-4">
                <span class="text-gray-600">
                    {{ __('web.ongkos_kirim') }}
                </span>

                <span class="font-semibold" id="tampil-ongkir">
                    —
                </span>
            </div>

            {{-- TOTAL --}}
            <div class="flex justify-between gap-4 mt-4 mb-6">
                <span class="text-lg font-bold">
                    {{ __('web.total') }}
                </span>

                <span
                    class="text-2xl font-bold text-blue-600 whitespace-nowrap"
                    id="tampil-total"
                    data-subtotal="{{ (int) $totalHarga }}"
                >
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

{{-- JAVASCRIPT PILIHAN WILAYAH DAN KURIR --}}
@php
    $teksCheckout = [
        'kota' => __('web.pilih_kota'),
        'kecamatan' => __('web.pilih_kecamatan'),
        'alamat' => __('web.pilih_alamat_dulu'),
        'memuat' => __('web.memuat'),
        'kosong' => __('web.tidak_ada_layanan'),
        'estimasi' => __('web.estimasi'),
    ];

    $alamatCheckout = [
        'kota' => url('ongkir/kota'),
        'kecamatan' => url('ongkir/kecamatan'),
        'hitung' => route('ongkir.hitung'),
    ];
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {

    const teks = @js($teksCheckout);
    const alamat = @js($alamatCheckout);

    const form = document.getElementById('form-checkout');
    const token     = form.querySelector('input[name="_token"]').value;

    const pProvinsi = document.getElementById('provinsi_id');
    const pKota     = document.getElementById('kota_id');
    const pKec      = document.getElementById('kecamatan_id');

    const kodePos   = document.getElementById('kode_pos');
    const daftar    = document.getElementById('daftar-layanan');

    const tOngkir   = document.getElementById('tampil-ongkir');
    const tTotal    = document.getElementById('tampil-total');

    const subtotal  = Number(tTotal.dataset.subtotal);

    const rupiah = (n) =>
        'Rp ' + Number(n).toLocaleString('id-ID');

    // Mengambil data dari endpoint Laravel.
    async function ambil(url, opsi = {}) {

        const jawaban = await fetch(url, {
            ...opsi,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
        });

        const isi = await jawaban.json();

        if (!jawaban.ok) {
            throw new Error(
                isi.pesan || isi.message || 'Terjadi kesalahan.'
            );
        }

        return isi;
    }

    // Mengisi pilihan dropdown.
    function isiPilihan(pilihan, data, teksAwal, terpilih) {

        pilihan.innerHTML = '';

        pilihan.add(new Option(teksAwal, ''));

        data.forEach(function (baris) {

            const opsi = new Option(baris.nama, baris.id);

            if (baris.kode_pos) {
                opsi.dataset.kodePos = baris.kode_pos;
            }

            if (String(baris.id) === String(terpilih)) {
                opsi.selected = true;
            }

            pilihan.add(opsi);
        });

        pilihan.disabled = data.length === 0;
    }

    // Mengembalikan ringkasan sebelum ongkir dipilih.
    function aturUlangLayanan(pesan) {

        daftar.textContent = pesan;

        tOngkir.textContent = '—';

        tTotal.textContent = rupiah(subtotal);
    }

    // Memuat kota berdasarkan provinsi.
    async function muatKota(terpilih) {

        isiPilihan(pKota, [], teks.kota);

        isiPilihan(pKec, [], teks.kecamatan);

        aturUlangLayanan(teks.alamat);

        if (!pProvinsi.value) return;

        pKota.innerHTML =
            '<option>' + teks.memuat + '</option>';

        try {

            const data = await ambil(
                alamat.kota + '/' + pProvinsi.value
            );

            isiPilihan(
                pKota,
                data,
                teks.kota,
                terpilih
            );

        } catch (e) {

            isiPilihan(pKota, [], teks.kota);

            aturUlangLayanan(e.message);
        }
    }

    // Memuat kecamatan berdasarkan kota.
    async function muatKecamatan(terpilih) {

        isiPilihan(pKec, [], teks.kecamatan);

        aturUlangLayanan(teks.alamat);

        if (!pKota.value) return;

        try {

            const data = await ambil(
                alamat.kecamatan + '/' + pKota.value
            );

            isiPilihan(
                pKec,
                data,
                teks.kecamatan,
                terpilih
            );

        } catch (e) {

            aturUlangLayanan(e.message);
        }
    }

    // Memuat pilihan layanan kurir.
    async function muatLayanan(terpilih) {

        const opsi = pKec.selectedOptions[0];

        if (opsi && opsi.dataset.kodePos) {
            kodePos.value = opsi.dataset.kodePos;
        }

        if (!pKec.value) {
            return aturUlangLayanan(teks.alamat);
        }

        aturUlangLayanan(teks.memuat);

        try {

            const hasil = await ambil(alamat.hitung, {
                method: 'POST',

                body: new URLSearchParams({
                    kecamatan_id: pKec.value,
                }),
            });

            tampilkanLayanan(
                hasil.layanan,
                terpilih
            );

        } catch (e) {

            aturUlangLayanan(e.message);
        }
    }

    // Menampilkan layanan kurir.
    function tampilkanLayanan(daftarLayanan, terpilih) {

        daftar.innerHTML = '';

        if (daftarLayanan.length === 0) {
            return aturUlangLayanan(teks.kosong);
        }

        daftarLayanan.forEach(function (l) {

            const label = document.createElement('label');

            label.className =
                'flex items-center justify-between p-3 border border-gray-300 rounded-lg mb-2 cursor-pointer';

            const kiri = document.createElement('span');

            const radio = document.createElement('input');

            radio.type = 'radio';
            radio.name = 'layanan';
            radio.value = l.kode;
            radio.dataset.biaya = l.biaya;
            radio.className = 'mr-2';

            radio.checked = l.kode === terpilih;

            radio.addEventListener(
                'change',
                perbaruiRingkasan
            );

            const nama = document.createElement('span');

            nama.textContent =
                l.kurir + ' ' + l.layanan +
                (l.estimasi
                    ? ' · ' + teks.estimasi + ' ' + l.estimasi
                    : '');

            const biaya = document.createElement('span');

            biaya.className = 'font-semibold';

            biaya.textContent = rupiah(l.biaya);

            kiri.append(radio, nama);

            label.append(kiri, biaya);

            daftar.append(label);
        });

        perbaruiRingkasan();
    }

    // Memperbarui ongkir dan total di halaman.
    function perbaruiRingkasan() {

        const dipilih = daftar.querySelector(
            'input[name="layanan"]:checked'
        );

        if (!dipilih) return;

        const biaya = Number(dipilih.dataset.biaya);

        tOngkir.textContent = rupiah(biaya);

        tTotal.textContent = rupiah(subtotal + biaya);
    }

    // Event pilihan provinsi.
    pProvinsi.addEventListener('change', function () {
        muatKota(null);
    });

    // Event pilihan kota.
    pKota.addEventListener('change', function () {
        muatKecamatan(null);
    });

    // Event pilihan kecamatan.
    pKec.addEventListener('change', function () {
        muatLayanan(null);
    });

    // Memulihkan pilihan lama jika validasi sebelumnya gagal.
    (async function () {

        if (!pProvinsi.value) return;

        await muatKota(pKota.dataset.lama);

        if (!pKota.value) return;

        await muatKecamatan(pKec.dataset.lama);

        if (!pKec.value) return;

        await muatLayanan(daftar.dataset.lama);

    })();

});
</script>
@endsection
