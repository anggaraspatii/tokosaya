<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - TokoSaya</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-red-600 via-red-500 to-brown-600">

    <div class="min-h-screen flex items-center justify-center px-4">

        <div class="w-full max-w-md">

            <!-- Logo / Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                    <span class="text-3xl font-bold text-blue-600">
                        TS
                    </span>
                </div>

                <h1 class="text-3xl font-bold text-white">
                    TokoSaya
                </h1>

                <p class="text-blue-100 mt-2">
                    Back Office Management
                </p>
            </div>


            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Selamat Datang 👋
                    </h2>

                    <p class="text-gray-500 mt-1">
                        Silakan login untuk melanjutkan
                    </p>
                </div>


                <!-- Error Message -->
                @if ($errors->any())
                    <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">

                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                        <div>
                            <p class="font-semibold">
                                Login gagal
                            </p>

                            <p class="text-sm mt-1">
                                {{ $errors->first() }}
                            </p>
                        </div>

                    </div>
                @endif


                <!-- Form -->
                <form method="POST" action="{{ route('login.process') }}" class="space-y-5">

                    @csrf


                    <!-- Email -->
                    <div>
                        <label for="email"
                               class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>

                        <div class="relative">

                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

                                <svg class="w-5 h-5 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"/>

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 14c4.418 0 8 1.79 8 4v1H4v-1c0-2.21 3.582-4 8-4z"/>
                                </svg>

                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder=""
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       focus:border-blue-500 transition"
                                required
                            >

                        </div>
                    </div>


                    <!-- Password -->
                    <div>
                        <label for="password"
                               class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>

                        <div class="relative">

                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

                                <svg class="w-5 h-5 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z"/>
                                </svg>

                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Masukkan password"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       focus:border-blue-500 transition"
                                required
                            >

                        </div>
                    </div>


                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700
                               text-white font-semibold py-3 rounded-xl
                               shadow-md hover:shadow-lg
                               transition duration-200
                               focus:outline-none focus:ring-2
                               focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Login ke Back Office
                    </button>

                </form>


                <!-- Footer -->
                <div class="mt-8 pt-5 border-t border-gray-100 text-center">

                    <p class="text-sm text-gray-400">
                        © {{ date('Y') }} TokoSaya
                    </p>

                </div>

            </div>

        </div>

    </div>

</body>
</html>
