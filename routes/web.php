
<?php

use Illuminate\Support\Facades\Route;
use App\Models\Produk;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController as ProdukPublikController;
use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\KategoriController;
use App\Http\Controllers\BackOffice\ProdukController as ProdukAdminController;
use App\Http\Controllers\BahasaController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BackOffice\PesananController;

Route::get('/', function () {
    $produk = Produk::with('kategori')
        ->where('status', true)
        ->latest()
        ->get();

    return view('user_front.home', compact('produk'));
})->name('home');

Route::get('/admin', function () {
    return view('back_office.dashboard');
});

Route::get('/kontak', function () {
    return view('user_front.kontak');
})->name('kontak');

Route::get('/bahasa/{bahasa}', [BahasaController::class, 'ubah'])
    ->name('bahasa.ubah');


// Katalog publik
Route::get('/produk', [ProdukPublikController::class, 'index'])
    ->name('produk.index');


 // Keranjang belanja
Route::get('/keranjang', [KeranjangController::class, 'index'])
    ->name('keranjang.index');

Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])
    ->name('keranjang.tambah');

Route::patch('/keranjang/{produkId}', [KeranjangController::class, 'update'])
    ->name('keranjang.update');

Route::delete('/keranjang/{produkId}', [KeranjangController::class, 'hapus'])
    ->name('keranjang.hapus');

 // Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/checkout/sukses', [CheckoutController::class, 'sukses'])
    ->name('checkout.sukses');

Route::delete('/keranjang', [KeranjangController::class, 'kosongkan'])
    ->name('keranjang.kosongkan');

Route::get('/produk/{slug}', [ProdukPublikController::class, 'show'])
    ->name('produk.show');

// Login dan logout
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Back Office
Route::middleware('pastikan.admin')
    ->prefix('back-office')
    ->name('back-office.')
    ->group(function () {
        Route::resource('produk', ProdukAdminController::class);

        Route::get('/pesanan', [PesananController::class, 'index'])
    ->name('pesanan.index');
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])
    ->name('pesanan.show');
    Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus'])
    ->name('pesanan.update-status');
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/back-office-produk', function () {
            return view('back_office.produk.index');
        })->name('back-office-produk');

        Route::resource('kategori', KategoriController::class);
        Route::resource('produk', ProdukAdminController::class);
    });
