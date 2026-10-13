<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produk - Back Office TokoSaya</title>

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
                        class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition">
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </nav>


    <!-- Content -->
    <main class="max-w-7xl mx-auto px-6 py-10">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h2 class="text-3xl font-bold text-gray-800">
                    Produk
                </h2>

                <p class="text-gray-500 mt-1">
                    Kelola produk TokoSaya.
                </p>

            </div>


            <a
                href="{{ route('back-office.produk.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold transition">

                + Tambah Produk

            </a>

        </div>


        <!-- Success -->
        @if (session('success'))

            <div class="mb-5 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50 border-b">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                No
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                Produk
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                Kategori
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                Harga
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                Stok
                            </th>

                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @forelse ($produks as $produk)

                            <tr class="hover:bg-gray-50">

                                <!-- No -->
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>


                                <!-- Produk -->
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-4">

                                        @if ($produk->gambar)

                                            <img
                                                src="{{ asset('storage/' . $produk->gambar) }}"
                                                alt="{{ $produk->nama }}"
                                                class="w-16 h-16 object-cover rounded-lg border">

                                        @else

                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                                                No Image
                                            </div>

                                        @endif


                                        <div>

                                            <p class="font-semibold text-gray-800">
                                                {{ $produk->nama }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $produk->slug }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <!-- Kategori -->
                                <td class="px-6 py-4">

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">

                                        {{ $produk->kategori->nama }}

                                    </span>

                                </td>


                                <!-- Harga -->
                                <td class="px-6 py-4 font-semibold text-gray-800">

                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}

                                </td>


                                <!-- Stok -->
                                <td class="px-6 py-4">

                                    {{ $produk->stok }}

                                </td>


                                <!-- Status -->
                                <td class="px-6 py-4 text-center">

                                    @if ($produk->status)

                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">

                                            Aktif

                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>


                                <!-- Aksi -->
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a
                                            href="{{ route('back-office.produk.edit', $produk) }}"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm">

                                            Edit

                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('back-office.produk.destroy', $produk) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm">

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="px-6 py-10 text-center text-gray-500">

                                    Belum ada produk.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</body>

</html>
