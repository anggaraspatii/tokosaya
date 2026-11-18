<?php

return [

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'produksi'   => (bool) env('MIDTRANS_PRODUKSI', false),

    /* Kunci untuk memeriksa tanda tangan notifikasi.
       Kalau MIDTRANS_SIGNATURE_KEY kosong, Server Key yang dipakai.
       Lihat penjelasan di F.2.1 */
    'signature_key' => env('MIDTRANS_SIGNATURE_KEY') ?: env('MIDTRANS_SERVER_KEY'),

    /* Alamat untuk membuat transaksi Snap */
    'snap_url' => env('MIDTRANS_PRODUKSI', false)
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    /* Alamat untuk membaca status transaksi */
    'api_url' => env('MIDTRANS_PRODUKSI', false)
        ? 'https://api.midtrans.com'
        : 'https://api.sandbox.midtrans.com',

];
