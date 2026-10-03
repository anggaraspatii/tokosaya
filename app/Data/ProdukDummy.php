<?php

namespace App\Data;

class ProdukDummy
{
    public static function semua(): array
    {
        return [
            [
                'id' => 1,
                'nama' => 'Kaos Polos',
                'kategori' => 'Pakaian',
                'harga' => 75000,
                'deskripsi' => 'Kaos polos nyaman untuk digunakan sehari-hari.',
            ],
            [
                'id' => 2,
                'nama' => 'Sepatu Sneakers',
                'kategori' => 'Sepatu',
                'harga' => 250000,
                'deskripsi' => 'Sepatu sneakers untuk aktivitas sehari-hari.',
            ],
            [
                'id' => 3,
                'nama' => 'Tas Ransel',
                'kategori' => 'Aksesoris',
                'harga' => 150000,
                'deskripsi' => 'Tas ransel praktis untuk sekolah dan bepergian.',
            ],
        ];
    }
}
