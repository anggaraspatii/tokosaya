
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('web.login') }} - TokoSaya</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-red-600 via-red-500 to-amber-600">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                    <span class="text-3xl font-bold text-blue-600">TS</span>
                </div>

                <h1 class="text-3xl font-bold text-white">TokoSaya</h1>

                <p class="text-blue-100 mt-2">
                    {{ __('web.back_office') }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ __('web.welcome') }} 👋
                    </h2>

                    <p class="text-gray-500 mt-1">
                        {{ __('web.login_instruction') }}
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <div>
                            <p class="font-semibold">
                                {{ __('web.login_failed') }}
                            </p>

                            <p class="text-sm mt-1">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('web.email') }}
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('web.password') }}
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="{{ __('web.password_placeholder') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        {{ __('web.login_back_office') }}
                    </button>
                </form>

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
