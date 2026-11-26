
@extends('back_office.layouts.app')

@section('judul-halaman', 'Detail Pesanan')

@section('konten')
<div class="container-fluid py-4">

    {{-- INFORMASI PESANAN --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Informasi Pesanan</h3>
        </div>

        <div class="card-body">
            <p>
                <strong>Nomor Pesanan:</strong>
                {{ $pesanan->nomor_pesanan }}
            </p>

            <p>
                <strong>Tanggal:</strong>
                {{ $pesanan->created_at->format('d-m-Y H:i') }}
            </p>

            <p>
                <strong>Status Saat Ini:</strong>
                <span class="badge text-bg-primary">
                    {{ ucfirst($pesanan->status) }}
                </span>
            </p>

            <form action="{{ route('back-office.pesanan.update-status', $pesanan->id) }}"
                  method="POST"
                  class="mt-3 mb-3">
                @csrf
                @method('PATCH')

                <label for="status" class="form-label">
                    Ubah Status Pesanan
                </label>

                <div class="d-flex gap-2">
                    <select name="status" id="status" class="form-select" required>
                        <option value="baru" {{ $pesanan->status === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="diproses" {{ $pesanan->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ $pesanan->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ $pesanan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ $pesanan->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

                @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </form>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <hr>

            <p>
                <strong>Nama Pemesan:</strong>
                {{ $pesanan->nama_pemesan }}
            </p>

            <p>
                <strong>Telepon:</strong>
                {{ $pesanan->telepon }}
            </p>

            <p>
                <strong>Alamat:</strong>
                {{ $pesanan->alamat }}
            </p>

            <p>
                <strong>Catatan:</strong>
                {{ $pesanan->catatan ?: '-' }}
            </p>

            <p class="mb-0">
                <strong>Total Pesanan:</strong>
                Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- INFORMASI PENGIRIMAN --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Pengiriman</h3>
        </div>

        <div class="card-body">
            <dl class="row mb-0">

                <dt class="col-sm-4">Kurir</dt>
                <dd class="col-sm-8">
                    {{ $pesanan->kurir ?? '—' }}
                    {{ $pesanan->layanan_kurir ?? '' }}
                </dd>

                <dt class="col-sm-4">Estimasi</dt>
                <dd class="col-sm-8">
                    {{ $pesanan->estimasi_kirim ?: '—' }}
                </dd>

                <dt class="col-sm-4">Berat</dt>
                <dd class="col-sm-8">
                    @if (!is_null($pesanan->berat_total))
                        {{ number_format((float) $pesanan->berat_total, 0, ',', '.') }} gram
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-4">Ongkos Kirim</dt>
                <dd class="col-sm-8">
                    @if (!is_null($pesanan->ongkir))
                        Rp {{ number_format((float) $pesanan->ongkir, 0, ',', '.') }}
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-4">Tujuan</dt>
                <dd class="col-sm-8">
                    {{ $pesanan->kecamatan ?: '—' }},
                    {{ $pesanan->kota ?: '—' }}<br>

                    {{ $pesanan->provinsi ?: '—' }}
                    {{ $pesanan->kode_pos ?: '' }}
                </dd>

            </dl>
        </div>
    </div>

    {{-- INFORMASI PEMBAYARAN --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Informasi Pembayaran</h3>
        </div>

        <div class="card-body">
            @php
                [$labelBayar, $warnaBayar] = $pesanan->labelBayar();
                $pembayaranTerakhir = $pesanan->pembayaran->first();
            @endphp

            <p>
                <strong>Status Pembayaran:</strong>
                <span class="badge text-bg-{{ $warnaBayar }}">
                    {{ $labelBayar }}
                </span>
            </p>

            <p>
                <strong>Order ID Midtrans:</strong>
                {{ $pesanan->midtrans_order_id ?: '-' }}
            </p>

            <p>
                <strong>Metode Pembayaran:</strong>
                {{ $pesanan->metode_bayar ?: '-' }}
            </p>

            <p>
                <strong>Dibayar Pada:</strong>
                {{ $pesanan->dibayar_pada?->format('d-m-Y H:i:s') ?? '-' }}
            </p>

            @if ($pembayaranTerakhir)
                <p>
                    <strong>Transaction ID:</strong>
                    {{ $pembayaranTerakhir->transaction_id ?: '-' }}
                </p>

                <p class="mb-0">
                    <strong>Transaction Status:</strong>
                    {{ $pembayaranTerakhir->transaction_status ?: '-' }}
                </p>
            @endif
        </div>
    </div>

    {{-- RIWAYAT NOTIFIKASI MIDTRANS --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Riwayat Notifikasi Midtrans</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Transaction Status</th>
                        <th>Nominal</th>
                        <th>Signature Sah</th>
                        <th>Waktu</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pesanan->pembayaran as $bayar)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $bayar->transaction_status ?: '-' }}
                            </td>

                            <td>
                                Rp {{ number_format((float) $bayar->gross_amount, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($bayar->signature_sah)
                                    <span class="badge text-bg-success">
                                        Valid
                                    </span>
                                @else
                                    <span class="badge text-bg-danger">
                                        Tidak Sah
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $bayar->created_at->format('d-m-Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada riwayat notifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PRODUK DALAM PESANAN --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Produk dalam Pesanan</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pesanan->detailPesanans as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->nama_produk }}</td>

                            <td>
                                Rp {{ number_format((float) $detail->harga, 0, ',', '.') }}
                            </td>

                            <td>{{ $detail->jumlah }}</td>

                            <td>
                                Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada detail produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('back-office.pesanan.index') }}"
               class="btn btn-secondary">
                Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

</div>
@endsection
