<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori - TokoSaya</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white px-6 py-4 shadow-md">

        <div class="flex justify-between items-center">

            <h1 class="font-bold text-xl">
                TokoSaya - Back Office
            </h1>

            <div class="flex items-center gap-4">

                <span>
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold">
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </nav>


    <!-- Content -->
    <main class="max-w-3xl mx-auto px-6 py-10">

        <div class="mb-6">

            <a
                href="{{ route('back-office.kategori.index') }}"
                class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Kembali ke Kategori
            </a>

        </div>


        <div class="bg-white rounded-xl shadow p-8">

            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Tambah Kategori
            </h2>

            <p class="text-gray-500 mb-6">
                Tambahkan kategori produk baru.
            </p>


            <!-- Form -->
            <form
                method="POST"
                action="{{ route('back-office.kategori.store') }}">

                @csrf


                <!-- Nama -->
                <div class="mb-6">

                    <label
                        for="nama"
                        class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Atasan"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >

                    @error('nama')
                        <p class="text-red-600 text-sm mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <!-- Buttons -->
                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white
                               px-5 py-3 rounded-lg font-semibold">
                        Simpan Kategori
                    </button>

                    <a
                        href="{{ route('back-office.kategori.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700
                               px-5 py-3 rounded-lg font-semibold">
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </main>

</body>

</html>
