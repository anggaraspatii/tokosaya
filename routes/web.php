<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackOffice\DashboardController;


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

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

   Route::get('/back-office/dashboard', [DashboardController::class, 'index'])
    ->middleware('pastikan.admin')
    ->name('back-office.dashboard');
