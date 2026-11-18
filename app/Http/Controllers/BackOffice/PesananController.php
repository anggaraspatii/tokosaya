<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('pembayaran')
            ->latest()
            ->paginate(10);

        return view(
            'back_office.pesanan.index',
            compact('pesanans')
        );
    }

    public function show(int $id)
    {
        $pesanan = Pesanan::with([
            'detailPesanans',
            'pembayaran'
        ])->findOrFail($id);

        return view(
            'back_office.pesanan.show',
            compact('pesanan')
        );
    }


public function updateStatus(Request $request, int $id)
{
    $request->validate([
        'status' => [
            'required',
            Rule::in([
                'baru',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan',
            ]),
        ],
    ]);

    $hasil = DB::transaction(function () use ($request, $id) {

        $pesanan = Pesanan::lockForUpdate()->findOrFail($id);

        // Pesanan lunas tidak boleh dibatalkan.
        if (
            $request->status === 'dibatalkan'
            && $pesanan->status_bayar === 'lunas'
        ) {
            return false;
        }

        // Pesanan yang masih menunggu pembayaran tidak boleh dibatalkan manual.
if (
    $request->status === 'dibatalkan'
    && $pesanan->status_bayar === 'pending'
) {
    return 'pending';
}

        // Pesanan belum lunas boleh dibatalkan.
        if (
            $request->status === 'dibatalkan'
            && $pesanan->status !== 'dibatalkan'
        ) {
            $pesanan->kembalikanStok();
        }

        // Pesanan belum lunas tidak boleh dikirim.
        if (
            $request->status === 'dikirim'
            && $pesanan->status_bayar !== 'lunas'
        ) {
            return null;
        }

        $pesanan->status = $request->status;
        $pesanan->save();

        return true;
    });

    if ($hasil === false) {
        return back()->with(
            'error',
            'Pesanan sudah lunas dan tidak dapat dibatalkan.'
        );
    }

    if ($hasil === 'pending') {
    return back()->with(
        'error',
        'Pembayaran masih menunggu konfirmasi Midtrans. Pesanan belum dapat dibatalkan.'
    );
}

    if ($hasil === null) {
        return back()->with(
            'error',
            'Pesanan belum lunas dan tidak dapat dikirim.'
        );
    }

    return redirect()
        ->route('back-office.pesanan.show', $id)
        ->with('success', 'Status pesanan berhasil diperbarui.');
}
}
