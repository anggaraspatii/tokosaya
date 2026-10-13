<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kategori - Back Office TokoSaya</title>

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
    <main class="max-w-6xl mx-auto px-6 py-10">

        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Kategori Produk
                </h2>

                <p class="text-gray-500 mt-1">
                    Kelola kategori produk TokoSaya.
                </p>
            </div>

            <a
                href="{{ route('back-office.kategori.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold transition">
                + Tambah Kategori
            </a>

        </div>


        <!-- Pesan sukses -->
        @if (session('success'))
            <div class="mb-5 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif


        <!-- Pesan error -->
        @if (session('error'))
            <div class="mb-5 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif


        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-50 border-b">

                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Nama Kategori
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Jumlah Produk
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                            Aksi
                        </th>
                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse ($kategoris as $kategori)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $kategori->nama }}
                            </td>

                            <td class="px-6 py-4">

                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                                    {{ $kategori->produks_count }} produk
                                </span>

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('back-office.kategori.edit', $kategori) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm">
                                        Edit
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('back-office.kategori.destroy', $kategori) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

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

                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                Belum ada kategori.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </main>

</body>

</html>
