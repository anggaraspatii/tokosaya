<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::latest()
            ->paginate(10);

        return view('back_office.pesanan.index', compact('pesanans'));
    }

    public function show(int $id)

    {
        $pesanan = Pesanan::with('detailPesanans')
            ->findOrFail($id);

        return view('back_office.pesanan.show', compact('pesanan'));
    }
        public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => [
                'required',
                Rule::in(['baru', 'diproses', 'dikirim', 'selesai', 'dibatalkan']),
            ],
        ]);

        $pesanan = Pesanan::findOrFail($id);

        $pesanan->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('back-office.pesanan.show', $pesanan->id)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
