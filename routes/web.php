<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;

Route::get('/', function () {
    return view('user_front.home');
})->name('home');

Route::get('/admin', function () {
    return view('back_office.dashboard');
});

Route::get('/kontak', function () {
    return view('user_front.kontak');
})->name('kontak');

Route::get('/produk', [ProdukController::class, 'index'])
    ->name('produk.index');

Route::get('/produk/{id}', [ProdukController::class, 'show'])
    ->name('produk.show');
