<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahPesananBaru = Pesanan::where('status', 'baru')->count();

        return view('back_office.dashboard', compact('jumlahPesananBaru'));
    }
}
