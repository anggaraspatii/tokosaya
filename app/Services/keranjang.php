<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class Keranjang
{
    private string $sessionKey = 'keranjang';

    public function semua(): array
    {
        $keranjang = Session::get($this->sessionKey, []);
        $hasil = [];

        foreach ($keranjang as $id => $item) {
            $produk = Produk::find($id);

            // Abaikan produk yang sudah dihapus admin.
            if (!$produk || !$produk->status) {
                continue;
            }

            $jumlah = (int) ($item['jumlah'] ?? 0);

            // Sinkronkan harga terbaru dari database.
            $hasil[$id] = [
                'produk' => $produk,
                'jumlah' => $jumlah,
                'harga' => $produk->harga,
                'subtotal' => $produk->harga * $jumlah,
            ];
        }

        Session::put($this->sessionKey, collect($hasil)->map(
            fn ($item) => [
                'jumlah' => $item['jumlah'],
            ]
        )->all());

        return $hasil;
    }

    public function tambah(int $produkId, int $jumlah = 1): void
    {
        $produk = Produk::where('status', true)->findOrFail($produkId);

        $keranjang = Session::get($this->sessionKey, []);
        $jumlahLama = (int) ($keranjang[$produkId]['jumlah'] ?? 0);
        $jumlahBaru = $jumlahLama + $jumlah;

        if ($jumlah < 1) {
            throw ValidationException::withMessages([
            'jumlah' => __('web.cart_min_quantity'),
        ]);
        }

        if ($jumlahBaru > $produk->stok) {
            throw ValidationException::withMessages([
            'jumlah' => __('web.cart_stock_error', [
            'stok' => $produk->stok,
    ]),
]);
        }

        $keranjang[$produkId] = [
            'jumlah' => $jumlahBaru,
        ];

        Session::put($this->sessionKey, $keranjang);
    }

    public function ubahJumlah(int $produkId, int $jumlah): void
    {
        $keranjang = Session::get($this->sessionKey, []);

        if (!isset($keranjang[$produkId])) {
            return;
        }

        if ($jumlah < 1) {
    throw ValidationException::withMessages([
        'jumlah' => __('web.cart_min_quantity'),
    ]);
}

        $produk = Produk::where('status', true)->find($produkId);

        if (!$produk) {
            unset($keranjang[$produkId]);
            Session::put($this->sessionKey, $keranjang);
            return;
        }

        if ($jumlah > $produk->stok) {
    throw ValidationException::withMessages([
        'jumlah' => __('web.cart_stock_error', [
            'stok' => $produk->stok,
        ]),
    ]);
}

        $keranjang[$produkId] = [
            'jumlah' => $jumlah,
        ];

        Session::put($this->sessionKey, $keranjang);
    }

    public function hapus(int $produkId): void
    {
        $keranjang = Session::get($this->sessionKey, []);

        unset($keranjang[$produkId]);

        Session::put($this->sessionKey, $keranjang);
    }

    public function kosongkan(): void
    {
        Session::forget($this->sessionKey);
    }

    public function jumlahItem(): int
    {
        return collect(Session::get($this->sessionKey, []))
            ->sum('jumlah');
    }

    public function totalHarga(): float
    {
        return (float) collect($this->semua())
            ->sum('subtotal');
    }
}
