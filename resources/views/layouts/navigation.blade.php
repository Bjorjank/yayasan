{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }"
    class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">

    <!-- Bar Atas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo + Nama --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-9 rounded-full">
                    <span class="font-semibold text-gray-800 text-lg tracking-wide">
                        Yayasan<span class="text-blue-600">Kita</span>
                    </span>
                </a>
            </div>

            {{-- Menu Tengah (Desktop) - pakai komponen Breeze --}}
            <div class="hidden md:flex items-center space-x-6">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Beranda') }}
                </x-nav-link>

                <x-nav-link :href="route('donation')" :active="request()->routeIs('donation')">
                    {{ __('Donasi') }}
                </x-nav-link>

                <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                    {{ __('Tentang') }}
                </x-nav-link>

                <x-nav-link :href="route('team')" :active="request()->routeIs('team')">
                    {{ __('Tim') }}
                </x-nav-link>

                <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                    {{ __('Kontak') }}
                </x-nav-link>

                @auth
                <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                    {{ __('Dasbor') }}
                </x-nav-link>
                @endauth
            </div>

            {{-- Auth (Desktop) --}}
            <div class="hidden md:flex items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-700 transition">
                            <!-- <img src="{{ asset('images/avatar-default.png') }}" class="h-6 w-6 rounded-full" alt="avatar"> -->
                            <span class="max-w-[10rem] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('user.dashboard')">
                            {{ __('Dasbor') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('user.donations.dashboard')">
                            {{ __('donasi saya') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-700 font-medium text-sm">
                        {{ __('Masuk') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 shadow transition">
                        {{ __('Daftar') }}
                    </a>
                </div>
                @endauth
            </div>

            {{-- Tombol Hamburger (Mobile) --}}
            <div class="md:hidden -me-2 flex items-center">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none transition"
                    aria-label="Buka navigasi" :aria-expanded="open">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Menu Mobile (Breeze responsive) --}}
    <div x-cloak :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('donation')" :active="request()->routeIs('donation')">
                {{ __('Donasi') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
                {{ __('Tentang') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('team')" :active="request()->routeIs('team')">
                {{ __('Tim') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                {{ __('Kontak') }}
            </x-responsive-nav-link>

            @auth
            <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                {{ __('Dasbor') }}
            </x-responsive-nav-link>
            @endauth
        </div>

        {{-- Opsi Akun (Mobile) --}}
        <div class="pt-4 pb-4 border-t border-gray-200 px-4">
            @auth
            <div class="mb-2">
                <div class="font-medium text-base text-gray-800 truncate">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 truncate">{{ auth()->user()->email }}</div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Masuk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Daftar') }}
                </x-responsive-nav-link>
            </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Hilangkan flash menu saat Alpine belum aktif --}}
<style>
    [x-cloak] {
        display: none !important
    }
</style>