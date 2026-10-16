
<?php

use Illuminate\Support\Facades\Route;
use App\Models\Produk;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController as ProdukPublikController;
use App\Http\Controllers\BackOffice\DashboardController;
use App\Http\Controllers\BackOffice\KategoriController;
use App\Http\Controllers\BackOffice\ProdukController as ProdukAdminController;
use App\Http\Controllers\BahasaController;

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
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/back-office-produk', function () {
            return view('back_office.produk.index');
        })->name('back-office-produk');

        Route::resource('kategori', KategoriController::class);
        Route::resource('produk', ProdukAdminController::class);
    });
