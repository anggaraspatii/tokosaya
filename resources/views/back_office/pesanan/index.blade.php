@extends('back_office.layouts.app')

@section('judul-halaman', 'Daftar Pesanan')

@section('konten')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pesanan</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nomor Pesanan</th>
                        <th>Nama Pemesan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pesanans as $pesanan)
                        <tr>
                            <td>{{ $loop->iteration + ($pesanans->currentPage() - 1) * $pesanans->perPage() }}</td>
                            <td>{{ $pesanan->nomor_pesanan }}</td>
                            <td>{{ $pesanan->nama_pemesan }}</td>
                            <td>{{ $pesanan->created_at->format('d-m-Y H:i') }}</td>
                            <td>Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge text-bg-primary">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('back-office.pesanan.show', $pesanan->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $pesanans->links() }}
        </div>
    </div>
</div>
@endsection
