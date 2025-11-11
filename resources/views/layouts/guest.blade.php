{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Brand Colors --}}
    <meta name="theme-color" content="#145EFC">
    <style>
        :root {
            --brand-blue: #145EFC;
            --brand-red: #D21F26;
            --brand-blue-weak: rgba(20, 94, 252, .06);
            --brand-red-weak: rgba(210, 31, 38, .06);
        }

        ::selection {
            background: var(--brand-blue);
            color: #fff;
        }
    </style>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    {{-- Background lembut (tanpa gradient campur warna utama di konten) --}}
    <div class="min-h-screen flex flex-col items-center pt-10 sm:pt-0"
        style="background: linear-gradient(
             to bottom,
             #ffffff 0%,
             #ffffff 40%,
             var(--brand-blue-weak) 78%,
             var(--brand-red-weak) 100%
         );">

        {{-- Aksen top: dua garis solid (biru lalu merah) --}}
        <div class="w-full">
            <div class="h-1 w-full" style="background: var(--brand-blue);"></div>
            <div class="h-1 w-full" style="background: var(--brand-red);"></div>
        </div>

        {{-- Logo brand --}}
        <div class="mt-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                {{-- pakai favicon atau ganti ke asset logo-mu --}}
                <img src="{{ asset('favicon.ico') }}" alt="Logo"
                    class="h-16 w-16 rounded-xl ring-1 ring-[color:var(--brand-blue)]/25 group-hover:ring-[color:var(--brand-blue)]/50 transition">
                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>

        {{-- Kartu Form --}}
        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/95 backdrop-blur shadow-sm sm:rounded-2xl ring-1 ring-gray-100">

            {{-- header kecil opsional (dengan ikon) --}}
            @hasSection('guest-title')
            <div class="mb-3 flex items-center gap-2 text-gray-900">
                <x-heroicon-o-lock-closed class="w-5 h-5 text-[color:var(--brand-blue)]" />
                <h1 class="text-lg font-semibold">@yield('guest-title')</h1>
            </div>
            @endif

            {{-- baris status mini (ikon dekoratif) --}}
            <div class="mb-4 flex items-center gap-3 text-xs">
                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full ring-1 ring-gray-200 bg-white">
                    <x-heroicon-o-shield-check class="w-4 h-4 text-[color:var(--brand-blue)]" />
                    <span class="text-gray-600">Aman & Terverifikasi</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full ring-1 ring-gray-200 bg-white">
                    <x-heroicon-o-heart class="w-4 h-4 text-[color:var(--brand-red)]" />
                    <span class="text-gray-600">Dukungan Responsif</span>
                </span>
            </div>

            {{-- SLOT FORM (login/register/forgot) --}}
            {{ $slot }}

            {{-- divider kecil: dua garis solid (biru & merah) --}}
            <div class="mt-6 space-y-1">
                <div class="h-[2px] w-full" style="background: var(--brand-blue);"></div>
                <div class="h-[2px] w-full" style="background: var(--brand-red);"></div>
            </div>
        </div>

        {{-- link bantuan opsional (dengan ikon) --}}
        @hasSection('guest-links')
        <div class="mt-4 text-sm text-gray-600 flex items-center gap-2">
            <x-heroicon-o-question-mark-circle class="w-5 h-5 text-[color:var(--brand-blue)]" />
            @yield('guest-links')
        </div>
        @endif

        {{-- footer mini --}}
        <div class="mt-8 mb-8 text-xs text-gray-500 flex items-center gap-2">
            <x-heroicon-o-sparkles class="w-4 h-4 text-[color:var(--brand-red)]" />
            <span>&copy; {{ date('Y') }} {{ config('app.name', 'YayasanKita') }} • Dibangun dengan peduli</span>
        </div>
    </div>
</body>

</html>