<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view("user_front.home");
});

Route::get('/admin', function () {
    return view('back_office.dashboard');
});

Route::get('/contact', function () {
    return view('contact');
});
