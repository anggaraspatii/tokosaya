@extends('back_office.layouts.app')

@section('judul-halaman', 'Dashboard')

@section('konten')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dashboard TokoSaya</h3>
        </div>

        <div class="card-body">
            <p>
                Selamat datang,
                <strong>{{ auth()->user()->name }}</strong>.
            </p>

            <a href="{{ route('back-office.produk.index') }}"
               class="btn btn-primary">
                <i class="bi bi-box-seam me-1"></i>
                Halaman Produk
            </a>

            <a href="{{ route('back-office.pesanan.index') }}"
               class="btn btn-success">
                <i class="bi bi-receipt me-1"></i>
                Daftar Pesanan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $jumlahPesananBaru }}</h3>
                    <p>Pesanan Baru</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-cart"></i>
                </div>
                <a href="{{ route('back-office.pesanan.index') }}"
                   class="small-box-footer link-light">
                    Lihat Daftar Pesanan
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
