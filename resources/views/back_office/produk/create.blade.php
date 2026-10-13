```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - TokoSaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 text-white px-6 py-4 shadow-md">
        <div class="flex justify-between items-center">
            <h1 class="font-bold text-xl">TokoSaya - Back Office</h1>

            <div class="flex items-center gap-4">
                <span>{{ auth()->user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-10">

        <a href="{{ route('back-office.produk.index') }}"
            class="text-blue-600 hover:text-blue-800 font-semibold">
            ← Kembali ke Produk
        </a>

        <div class="bg-white rounded-xl shadow p-8 mt-5">

            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Tambah Produk
            </h2>

            <p class="text-gray-500 mb-6">
                Masukkan informasi produk fashion TokoSaya.
            </p>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 border border-red-200 rounded-lg p-4 mb-5">
                    <p class="font-semibold mb-2">Periksa kembali data berikut:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                action="{{ route('back-office.produk.store') }}"
                enctype="multipart/form-data">

                @csrf

                <div class="mb-5">
                    <label for="nama" class="block font-semibold text-gray-700 mb-2">
                        Nama Produk
                    </label>
                    <input type="text" id="nama" name="nama"
                        value="{{ old('nama') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                        placeholder="Contoh: Kemeja Linen Premium" required>
                </div>

                <div class="mb-5">
                    <label for="kategori_id" class="block font-semibold text-gray-700 mb-2">
                        Kategori
                    </label>
                    <select id="kategori_id" name="kategori_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                @selected(old('kategori_id') == $kategori->id)>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label for="harga" class="block font-semibold text-gray-700 mb-2">
                        Harga (Rupiah)
                    </label>
                    <input type="number" id="harga" name="harga"
                        value="{{ old('harga') }}" min="0" step="1000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                        placeholder="189000" required>
                </div>

                <div class="mb-5">
                    <label for="deskripsi" class="block font-semibold text-gray-700 mb-2">
                        Deskripsi Produk
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                        placeholder="Jelaskan bahan, model, dan kegunaan produk..."
                        required>{{ old('deskripsi') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">
                        Tulis minimal 2 kalimat.
                    </p>
                </div>

                <div class="mb-5">
                    <label for="stok" class="block font-semibold text-gray-700 mb-2">
                        Stok
                    </label>
                    <input type="number" id="stok" name="stok"
                        value="{{ old('stok', 0) }}" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                </div>

                <div class="mb-5">
                    <label for="gambar" class="block font-semibold text-gray-700 mb-2">
                        Gambar Produk
                    </label>
                    <input type="file" id="gambar" name="gambar"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                        required>
                    <p class="text-sm text-gray-500 mt-1">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>
                </div>

                <div class="mb-6">
                    <label for="status" class="block font-semibold text-gray-700 mb-2">
                        Status Produk
                    </label>
                    <select id="status" name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                        <option value="1" @selected(old('status', '1') == '1')>
                            Aktif
                        </option>
                        <option value="0" @selected(old('status') === '0')>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold">
                        Simpan Produk
                    </button>

                    <a href="{{ route('back-office.produk.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-lg font-semibold">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </main>

</body>
</html>
```
