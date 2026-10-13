
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - TokoSaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">TokoSaya Admin</h1>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg">
                Logout
            </button>
        </form>
    </nav>

    <main class="max-w-3xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Edit Produk</h2>

                <a href="{{ route('back-office.produk.index') }}"
                    class="text-blue-600 hover:underline">
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-5">
                    <p class="font-bold mb-2">Periksa kembali data berikut:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('back-office.produk.update', $produk->id) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium mb-2">Nama Produk</label>
                    <input type="text" name="nama"
                        value="{{ old('nama', $produk->nama) }}"
                        class="w-full border rounded-lg p-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Kategori</label>
                    <select name="kategori_id"
                        class="w-full border rounded-lg p-3" required>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                @selected(old('kategori_id', $produk->kategori_id) == $kategori->id)>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Harga (Rp)</label>
                    <input type="number" name="harga"
                        value="{{ old('harga', $produk->harga) }}"
                        min="0" step="1"
                        class="w-full border rounded-lg p-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full border rounded-lg p-3"
                        required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Stok</label>
                    <input type="number" name="stok"
                        value="{{ old('stok', $produk->stok) }}"
                        min="0"
                        class="w-full border rounded-lg p-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">Gambar Saat Ini</label>

                    @if ($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                            alt="{{ $produk->nama }}"
                            class="w-40 h-40 object-cover rounded-lg border">
                    @else
                        <p class="text-gray-500">Belum ada gambar.</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-2">
                        Ganti Gambar (Opsional)
                    </label>
                    <input type="file" name="gambar"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full border rounded-lg p-3">

                    <p class="text-sm text-gray-500 mt-1">
                        JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        Kosongkan jika tidak ingin mengganti gambar.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block font-medium mb-2">Status Produk</label>
                    <select name="status"
                        class="w-full border rounded-lg p-3" required>
                        <option value="1"
                            @selected((string) old('status', (int) $produk->status) === '1')>
                            Aktif
                        </option>
                        <option value="0"
                            @selected((string) old('status', (int) $produk->status) === '0')>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </main>

</body>
</html>
