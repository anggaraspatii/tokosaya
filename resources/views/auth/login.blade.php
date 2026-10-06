<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - TokoSaya</title>

    <link rel="stylesheet"
          href="{{ asset('assets/user_front/css/styles.css') }}">
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center">

        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">

            < class="text-2xl font-bold text-center mb-2">
            Login TokoSaya
            <h1 class="text-2xl font-bold text-center mb-2">
             Login TokoSaya
        </h1>

            <p class="text-center text-gray-500 mb-6">
             Silakan masuk untuk mengakses Back Office
             </p>


            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">

                @csrf

                <div class="mb-4">
                    <label class="block mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label class="block mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded"
                >
                    Login
                </button>

            </form>

        </div>

    </div>

</body>
</html>
