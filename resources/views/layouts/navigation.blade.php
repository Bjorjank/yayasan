{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }"
    class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">

    {{-- Brand accent (top thin gradient bar) --}}
    <div class="h-[2px] w-full" style="background: linear-gradient(to right, var(--brand-blue), var(--brand-red));"></div>

    {{-- Top bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo + Brand --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('favicon.ico') }}" alt="Logo"
                         class="h-9 w-9 rounded-full ring-1 ring-gray-200 group-hover:ring-gray-300 transition">
                    <span class="font-semibold text-lg tracking-wide text-gray-800">
                        Yayasan<span style="color: var(--brand-blue)">Kita</span>
                    </span>
                </a>
            </div>

            {{-- Primary nav (desktop) --}}
            <div class="hidden md:flex items-center space-x-6">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-gray-700 hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-home class="w-5 h-5" />
                        {{ __('Beranda') }}
                    </span>
                </x-nav-link>

                <x-nav-link :href="route('donation')" :active="request()->routeIs('donation')" class="text-gray-700 hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-currency-dollar class="w-5 h-5" />
                        {{ __('Donasi') }}
                    </span>
                </x-nav-link>

                <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-gray-700 hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-information-circle class="w-5 h-5" />
                        {{ __('Tentang') }}
                    </span>
                </x-nav-link>

                <x-nav-link :href="route('team')" :active="request()->routeIs('team')" class="text-gray-700 hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-users class="w-5 h-5" />
                        {{ __('Tim') }}
                    </span>
                </x-nav-link>

                <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-gray-700 hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-envelope class="w-5 h-5" />
                        {{ __('Kontak') }}
                    </span>
                </x-nav-link>

                @auth
                <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" class="text-gray-700 hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-rectangle-stack class="w-5 h-5" />
                        {{ __('Dasbor') }}
                    </span>
                </x-nav-link>
                @endauth
            </div>

            {{-- Auth (desktop) --}}
            <div class="hidden md:flex items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-600 ring-1 ring-gray-200 hover:ring-gray-300 transition">
                            <!-- <img src="{{ asset('images/avatar-default.png') }}" class="h-6 w-6 rounded-full ring-1 ring-gray-200" alt="avatar"> -->
                            <span class="max-w-[10rem] truncate">{{ auth()->user()->name }}</span>
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:text-blue-600">
                            <span class="inline-flex items-center gap-2">
                                <x-heroicon-o-user class="w-5 h-5" />
                                {{ __('Profil') }}
                            </span>
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('user.dashboard')" class="hover:text-blue-600">
                            <span class="inline-flex items-center gap-2">
                                <x-heroicon-o-rectangle-stack class="w-5 h-5" />
                                {{ __('Dasbor') }}
                            </span>
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('user.donations.dashboard')" class="hover:text-blue-600">
                            <span class="inline-flex items-center gap-2">
                                <x-heroicon-o-banknotes class="w-5 h-5" />
                                {{ __('Donasi Saya') }}
                            </span>
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                class="hover:text-red-600"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="inline-flex items-center gap-2">
                                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                                    {{ __('Keluar') }}
                                </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 text-gray-700 hover:text-blue-600 font-medium text-sm">
                        <x-heroicon-o-arrow-right-end-on-rectangle class="w-5 h-5" />
                        {{ __('Masuk') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-white shadow transition hover:brightness-95"
                       style="background: linear-gradient(90deg, var(--brand-blue), var(--brand-red));">
                        <x-heroicon-o-user-plus class="w-5 h-5" />
                        {{ __('Daftar') }}
                    </a>
                </div>
                @endauth
            </div>

            {{-- Hamburger (mobile) --}}
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

    {{-- Mobile menu --}}
    <div x-cloak
        :class="{'block': open, 'hidden': ! open}"
        class="hidden md:hidden bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="hover:text-blue-600">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-home class="w-5 h-5" />
                    {{ __('Beranda') }}
                </span>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('donation')" :active="request()->routeIs('donation')" class="hover:text-blue-600">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-currency-dollar class="w-5 h-5" />
                    {{ __('Donasi') }}
                </span>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" class="hover:text-blue-600">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-information-circle class="w-5 h-5" />
                    {{ __('Tentang') }}
                </span>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('team')" :active="request()->routeIs('team')" class="hover:text-blue-600">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-users class="w-5 h-5" />
                    {{ __('Tim') }}
                </span>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="hover:text-blue-600">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-envelope class="w-5 h-5" />
                    {{ __('Kontak') }}
                </span>
            </x-responsive-nav-link>

            @auth
            <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" class="hover:text-blue-600">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-rectangle-stack class="w-5 h-5" />
                    {{ __('Dasbor') }}
                </span>
            </x-responsive-nav-link>
            @endauth
        </div>

        {{-- Account (mobile) --}}
        <div class="pt-4 pb-4 border-t border-gray-200 px-4">
            @auth
            <div class="mb-2">
                <div class="font-medium text-base text-gray-800 truncate">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 truncate">{{ auth()->user()->email }}</div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-user class="w-5 h-5" />
                        {{ __('Profil') }}
                    </span>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('user.donations.dashboard')" class="hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-banknotes class="w-5 h-5" />
                        {{ __('Donasi Saya') }}
                    </span>
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        class="hover:text-red-600"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <span class="inline-flex items-center gap-2">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                            {{ __('Keluar') }}
                        </span>
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="space-y-2">
                <x-responsive-nav-link :href="route('login')" class="hover:text-blue-600">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-arrow-right-end-on-rectangle class="w-5 h-5" />
                        {{ __('Masuk') }}
                    </span>
                </x-responsive-nav-link>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center w-full px-4 py-2 rounded-lg text-sm font-medium text-white hover:brightness-95"
                   style="background: linear-gradient(90deg, var(--brand-blue), var(--brand-red));">
                    <x-heroicon-o-user-plus class="w-5 h-5 mr-2" />
                    {{ __('Daftar') }}
                </a>
            </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Prevent flash before Alpine mounts --}}
<style>
    [x-cloak] { display: none !important }
</style>
