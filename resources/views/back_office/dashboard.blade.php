<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Back Office - TokoSaya</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100">

 <nav class="bg-red-600 text-white px-6 py-4 shadow-md">

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

                    <button type="submit"
                            class="bg-blue-500 hover:bg-red-700 px-4 py-2 rounded font-semibold">
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </nav>

    <main class="container mx-auto px-6 py-10">

        <div class="bg-white p-8 rounded-lg shadow">

            <h2 class="text-3xl font-bold mb-4">
                Dashboard
            </h2>

            <p>
                Selamat datang,
                <strong>{{ auth()->user()->name }}</strong>.
            </p>

        </div>

    </main>

</body>
</html>
