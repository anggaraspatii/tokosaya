<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Kecamatan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\Keranjang;
use App\Services\Midtrans;
use App\Services\RajaOngkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index(Keranjang $keranjang)
    {
        $items = $keranjang->semua();

        if (count($items) === 0) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        $totalHarga = $keranjang->totalHarga();
        $totalBerat = $keranjang->totalBerat();

        // Ambil daftar provinsi dari RajaOngkir.
        try {
            $daftarProvinsi = RajaOngkir::provinsi();
        } catch (RuntimeException $e) {
            report($e);

            $daftarProvinsi = collect();

            session()->now(
                'error',
                __('web.ongkir_gagal')
            );
        }

        return view('user_front.checkout.index', compact(
            'items',
            'totalHarga',
            'totalBerat',
            'daftarProvinsi'
        ));
    }

    public function store(Request $request, Keranjang $keranjang)
    {
        // 1. Validasi data pembeli dan wilayah pengiriman.
        $validated = $request->validate([
            'nama_pemesan' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:2000'],
            'catatan' => ['nullable', 'string', 'max:2000'],

            'provinsi_id' => [
                'required',
                'integer',
                'exists:provinsis,id',
            ],

            'kota_id' => [
                'required',
                'integer',
                'exists:kotas,id',
            ],

            'kecamatan_id' => [
                'required',
                'integer',
                'exists:kecamatans,id',
            ],

            'layanan' => ['required', 'string', 'max:60'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
        ]);

        // 2. Periksa isi keranjang.
        $items = $keranjang->semua();

        if (count($items) === 0) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        // 3. Pastikan provinsi, kota, dan kecamatan saling cocok.
        $kecamatan = Kecamatan::with('kota.provinsi')
            ->find($validated['kecamatan_id']);

        if (
            ! $kecamatan ||
            ! $kecamatan->kota ||
            ! $kecamatan->kota->provinsi ||
            (int) $kecamatan->kota_id !== (int) $validated['kota_id'] ||
            (int) $kecamatan->kota->provinsi_id !== (int) $validated['provinsi_id']
        ) {
            return back()
                ->withErrors([
                    'kecamatan_id' => __('web.wilayah_tidak_cocok'),
                ])
                ->withInput();
        }

        // 4. Hitung berat keranjang dari server.
        $berat = $keranjang->totalBerat();

        if ($berat <= 0) {
            return back()
                ->with('error', __('web.keranjang_kosong'))
                ->withInput();
        }

        // 5. Hitung ulang ongkir dari RajaOngkir.
        // Harga ongkir dari browser tidak digunakan.
        try {
            $layanan = RajaOngkir::hitung(
                (int) $kecamatan->id,
                $berat
            )->firstWhere('kode', $validated['layanan']);

        } catch (RuntimeException $e) {
            report($e);

            return back()
                ->with('error', __('web.ongkir_gagal'))
                ->withInput();
        }

        // 6. Tolak layanan yang tidak tersedia.
        if (! $layanan) {
            return back()
                ->withErrors([
                    'layanan' => __('web.layanan_tidak_berlaku'),
                ])
                ->withInput();
        }

        $ongkir = (int) $layanan['biaya'];

        // 7. Siapkan data pengiriman yang akan disimpan.
        $pengiriman = [
            'provinsi' => $kecamatan->kota->provinsi->nama,
            'kota' => $kecamatan->kota->nama,
            'kecamatan' => $kecamatan->nama,
            'kecamatan_id' => $kecamatan->id,

            'kode_pos' => $validated['kode_pos']
                ?: $kecamatan->kode_pos,

            'kurir' => $layanan['kurir'],
            'layanan_kurir' => $layanan['layanan'],
            'estimasi_kirim' => $layanan['estimasi'],
            'berat_total' => $berat,
            'ongkir' => $ongkir,
        ];

        try {

            // 8. Simpan pesanan dan kurangi stok dalam transaksi.
            $pesanan = DB::transaction(function () use (
                $items,
                $validated,
                $pengiriman,
                $ongkir
            ) {
                $dataProduk = [];
                $subtotal = 0;

                foreach ($items as $id => $item) {

                    $produk = Produk::whereKey($id)
                        ->lockForUpdate()
                        ->first();

                    $jumlah = (int) $item['jumlah'];

                    if (
                        ! $produk ||
                        ! $produk->status ||
                        $jumlah < 1 ||
                        $produk->stok < $jumlah
                    ) {
                        throw ValidationException::withMessages([
                            'keranjang' => 'Stok produk tidak mencukupi.',
                        ]);
                    }

                    $harga = (float) $produk->harga;
                    $subtotalProduk = $harga * $jumlah;

                    $dataProduk[] = [
                        'produk' => $produk,
                        'jumlah' => $jumlah,
                        'harga' => $harga,
                        'subtotal' => $subtotalProduk,
                    ];

                    $subtotal += $subtotalProduk;
                }

                // Total pesanan sudah termasuk ongkos kirim.
                $totalHarga = $subtotal + $ongkir;

                $pesanan = Pesanan::create(array_merge([
                    'nomor_pesanan' => 'ORD-'
                        . now()->format('YmdHis')
                        . '-'
                        . Str::upper(Str::random(5)),

                    'nama_pemesan' => $validated['nama_pemesan'],
                    'telepon' => $validated['telepon'],
                    'alamat' => $validated['alamat'],
                    'catatan' => $validated['catatan'] ?? null,

                    'total_harga' => $totalHarga,

                    'status' => 'baru',
                    'status_bayar' => 'belum_bayar',

                ], $pengiriman));

                // Simpan detail produk dan kurangi stok.
                foreach ($dataProduk as $data) {

                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $data['produk']->id,
                        'nama_produk' => $data['produk']->nama,
                        'harga' => $data['harga'],
                        'jumlah' => $data['jumlah'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    $data['produk']->decrement(
                        'stok',
                        $data['jumlah']
                    );
                }

                return $pesanan;
            });

            // 9. Buat halaman pembayaran Midtrans.
            $urlPembayaran = Midtrans::mintaHalamanBayar($pesanan);

            // 10. Simpan ID pesanan untuk halaman sukses.
            $request->session()->put(
                'pesanan_sukses_id',
                $pesanan->id
            );

            // 11. Kosongkan keranjang setelah halaman pembayaran berhasil dibuat.
            $keranjang->kosongkan();

            // 12. Arahkan pembeli ke halaman pembayaran.
            return redirect()->away($urlPembayaran);

        } catch (RuntimeException $e) {

            report($e);

            return redirect()
                ->route('checkout.index')
                ->with(
                    'error',
                    'Pembayaran gagal dibuat. Silakan coba lagi.'
                );
        }
    }

    public function sukses(Request $request)
    {
        $pesananId = $request->session()->get(
            'pesanan_sukses_id'
        );

        if (! $pesananId) {
            return redirect()
                ->route('home')
                ->with('error', 'Data pesanan tidak ditemukan.');
        }

        $pesanan = Pesanan::with([
            'detailPesanans',
            'pembayaran',
        ])->find($pesananId);

        if (! $pesanan) {
            $request->session()->forget('pesanan_sukses_id');

            return redirect()->route('home');
        }

        return view(
            'user_front.checkout.sukses',
            compact('pesanan')
        );
    }
}
