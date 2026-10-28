@extends('back_office.layouts.app')

@section('judul-halaman', 'Detail Pesanan')

@section('konten')
<div class="container-fluid py-4">

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Informasi Pesanan</h3>
        </div>

        <div class="card-body">
            <p><strong>Nomor Pesanan:</strong> {{ $pesanan->nomor_pesanan }}</p>
            <p><strong>Tanggal:</strong> {{ $pesanan->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Status Saat Ini:</strong>
    <span class="badge text-bg-primary">
        {{ ucfirst($pesanan->status) }}
    </span>
</p>

<form action="{{ route('back-office.pesanan.update-status', $pesanan->id) }}"
      method="POST"
      class="mt-3 mb-3">
    @csrf
    @method('PATCH')

    <label for="status" class="form-label">Ubah Status Pesanan</label>

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
            <p><strong>Nama Pemesan:</strong> {{ $pesanan->nama_pemesan }}</p>
            <p><strong>Telepon:</strong> {{ $pesanan->telepon }}</p>
            <p><strong>Alamat:</strong> {{ $pesanan->alamat }}</p>
            <p><strong>Catatan:</strong> {{ $pesanan->catatan ?: '-' }}</p>
            <p><strong>Total Pesanan:</strong>
                Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Produk dalam Pesanan</h3>
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
                            <td>Rp {{ number_format((float) $detail->harga, 0, ',', '.') }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}</td>
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

            <a href="{{ route('back-office.pesanan.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

</div>
@endsection
