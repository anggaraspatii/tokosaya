<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('judul', config('app.name'))</title>

    <link rel="stylesheet" href="{{ asset('assets/user_front/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user_front/css/custom.css') }}">
</head>

<body>

    @include('user_front.partials.navbar')

    <main>
        @yield('konten')
    </main>

    @include('user_front.partials.footer')

    <script src="{{ asset('assets/user_front/js/script.js') }}"></script>

    @stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu-placeholder');

        if (!hamburger || !mobileMenu) {
            console.error('Elemen hamburger atau menu mobile tidak ditemukan.');
            return;
        }

        hamburger.addEventListener('click', function () {
            const menuAkanDibuka = mobileMenu.classList.contains('hidden');

            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');

            hamburger.setAttribute(
                'aria-expanded',
                String(menuAkanDibuka)
            );
        });
    });
</script>
</body>
</html>
