<!-- Header -->
    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <!-- Left section: Logo -->
            <a href="index.html" class="flex items-center">
              <div>
                  <img src="{{ asset('assets/user_front/images/template-white-logo.png') }}"
                    alt="Logo"
                    class="h-14 w-auto mr-4">
              </div>
            </a>

            <!-- Hamburger menu (for mobile) -->
            <button
            id="hamburger"
            type="button"
            class="text-white focus:outline-none lg:hidden"
            aria-label="Buka menu"
            aria-expanded="false">

            <svg class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path path
                stroke-linecap="round"
                stroke-linejoin="round"
                 stroke-width="2"
                d="M4 6h16M4 12h16m-7 6h7">
            </path>
            </svg>
        </button>
            <!-- Center section: Menu -->

<nav class="hidden lg:flex flex-1 items-center justify-center">
    <ul class="flex items-center justify-center space-x-4 text-white">
        <li>
            <a href="{{ route('home') }}" class="hover:text-secondary font-semibold">
                {{ __('web.home') }}
            </a>
        </li>

        <li>
            <a href="{{ route('produk.index') }}" class="hover:text-secondary font-semibold">
                {{ __('web.products') }}
            </a>
        </li>

        <li>
            <a href="{{ route('kontak') }}" class="hover:text-secondary font-semibold">
                {{ __('web.contact') }}
            </a>
        </li>
    </ul>
</nav>

<!-- Right section: Buttons for desktop -->
<div class="hidden lg:flex items-center space-x-4 relative">
    <!-- Language switcher -->
    <div class="flex items-center gap-2 text-white text-sm font-semibold">
        <a href="{{ route('bahasa.ubah', ['bahasa' => 'id']) }}"
           class="px-2 py-1 rounded {{ app()->getLocale() === 'id' ? 'bg-primary text-white' : 'hover:text-secondary' }}">
            ID
        </a>

        <span class="text-gray-400">|</span>

        <a href="{{ route('bahasa.ubah', ['bahasa' => 'en']) }}"
           class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-primary text-white' : 'hover:text-secondary' }}">
            EN
        </a>
    </div>

    <!-- Pertahankan elemen cart, search, dan search field kamu di sini -->
</div>


              <a href="register.html"
                  class="mr-7 bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">{{ __('web.register') }}</a>
              <a href="{{ route('login') }}"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">{{ __('web.login') }}</a>


<div class="relative cart-wrapper">
    <a href="{{ route('keranjang.index') }}"
       class="relative inline-flex items-center text-white"
       aria-label="{{ app()->getLocale() === 'en' ? 'Shopping cart' : 'Keranjang belanja' }}">

        <img src="{{ asset('assets/user_front/images/cart-shopping.svg') }}"
             alt="Cart"
             class="h-6 w-6">

         @php
    $jumlahKeranjang = app(\App\Services\Keranjang::class)->jumlahItem();
@endphp

@if ($jumlahKeranjang > 0)
    <span style="position: absolute; top: -8px; right: -8px; background: red; color: white; border-radius: 50%; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; padding: 2px;">
        {{ $jumlahKeranjang }}
    </span>
@endif
    </a>
</div>

              <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                 <img src="{{ asset('assets/user_front/images/search-icon.svg') }}" alt="Search"
                    class="h-6 w-6 transition-transform transform group-hover:scale-120">
              </a>
              <!-- Search field -->
              <div id="search-field"
                  class="hidden absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                  <input type="text" class="w-full p-2 border border-gray-300 rounded"
                      placeholder="Search for products...">
              </div>
          </div>
        </div>
    </header>

{{-- Mobile Menu --}}
<nav id="mobile-menu-placeholder"
    class="hidden lg:hidden flex-col gap-2 bg-gray-dark px-6 py-6 text-white">

    {{-- Home --}}
    <a href="{{ route('home') }}"
        class="block rounded-lg px-4 py-3 font-semibold hover:bg-white/10">
        {{ __('web.home') }}
    </a>

    {{-- Produk --}}
    <a href="{{ route('produk.index') }}"
        class="block rounded-lg px-4 py-3 font-semibold hover:bg-white/10">
        {{ __('web.products') }}
    </a>

    {{-- Kontak --}}
    <a href="{{ route('kontak') }}"
        class="block rounded-lg px-4 py-3 font-semibold hover:bg-white/10">
        {{ __('web.contact') }}
    </a>

    <div class="my-2 border-t border-white/20"></div>

    {{-- Pilihan Bahasa --}}
    <p class="block rounded-lg px-4 py-3 font-semibold hover:bg-white/10">
        Bahasa / Language
    </p>

    <div class="grid grid-cols-2 gap-3 px-4 py-2">
        <a href="{{ route('bahasa.ubah', ['bahasa' => 'id']) }}"
            class="rounded-lg border px-3 py-3 text-center font-semibold
            {{ app()->getLocale() === 'id'
                ? 'border-primary bg-primary text-white'
                : 'border-white/30 hover:bg-white/10' }}">
            🇮🇩 Bahasa Indonesia
        </a>

        <a href="{{ route('bahasa.ubah', ['bahasa' => 'en']) }}"
            class="rounded-lg border px-3 py-3 text-center font-semibold
            {{ app()->getLocale() === 'en'
                ? 'border-primary bg-primary text-white'
                : 'border-white/30 hover:bg-white/10' }}">
            🇬🇧 English
        </a>
    </div>

    <div class="my-2 border-t border-white/20"></div>

    {{-- Login --}}
    <a href="{{ route('login') }}"
        class="block rounded-full border border-primary bg-primary px-4 py-3 text-center font-semibold text-white hover:bg-transparent">
        {{ __('web.login') }}
    </a>

</nav>
