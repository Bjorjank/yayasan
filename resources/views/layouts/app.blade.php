{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Brand Colors (logo): Blue #145EFC, Red #D21F26 --}}
    <meta name="theme-color" content="#145EFC" />
    <style>
        :root {
            /* Tinggi bar navigasi (navbar fixed) */
            --nav-h: 4rem;
            /* h-16 = 64px */

            --brand-blue: #145EFC;
            --brand-red: #D21F26;
            --brand-blue-weak: rgba(20, 94, 252, .06);
            --brand-red-weak: rgba(210, 31, 38, .06);
        }

        /* Offset global agar konten tidak tertutup navbar fixed */
        .has-fixed-nav {
            padding-top: var(--nav-h);
        }

        /* Nyaman untuk anchor (#visi-misi, dll.) */
        .scroll-pad {
            scroll-margin-top: calc(var(--nav-h) + 12px);
        }

        ::selection {
            background: var(--brand-blue);
            color: #fff;
        }

        /* Scrollbar (WebKit) */
        *::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--brand-blue), var(--brand-red));
            border-radius: 999px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    @stack('head')
</head>

<body class="font-sans antialiased text-gray-900">
    {{-- Background lembut --}}
    <div class="min-h-screen has-fixed-nav" style="
        background:
            linear-gradient(to bottom, #ffffff 0%, #ffffff 40%, var(--brand-blue-weak) 85%, var(--brand-red-weak) 100%);
    ">
        {{-- Top Navigation (fixed di file navigation) --}}
        @include('layouts.navigation')

        {{-- Page Heading: mendukung <x-slot name="header"> atau @section('header') --}}
        @php $headerSlot = $header ?? null; @endphp
        @if (isset($headerSlot) || View::hasSection('header'))
        <header class="bg-white/95 backdrop-blur shadow-sm">
            {{-- Accent double bar (biru & merah) --}}
            <div class="w-full">
                <div class="h-[2px] w-full" style="background: var(--brand-blue);"></div>
                <div class="h-[2px] w-full" style="background: var(--brand-red);"></div>
            </div>

            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-start gap-3">
                    <div class="mt-1 hidden sm:flex items-center gap-2 shrink-0">
                        <x-heroicon-o-sparkles class="h-6 w-6 text-brand-blue" />
                        <x-heroicon-o-heart class="h-6 w-6 text-brand-red" />
                    </div>

                    <div class="min-w-0 grow">
                        @isset($headerSlot)
                        {{ $headerSlot }}
                        @endisset
                        @hasSection('header')
                        @yield('header')
                        @endif
                    </div>
                </div>
            </div>
        </header>
        @endif

        {{-- Page Content --}}
        <main class="contents">
            @if (View::hasSection('content'))
            @yield('content')
            @else
            {{-- Fallback jika dipakai sebagai <x-app-layout> --}}
            {{ $slot ?? '' }}
            @endif
        </main>
    </div>

    {{-- Widget lain (footer global sudah dipanggil di layout utama proyek, jangan duplicasi di page) --}}
    <x-chat-widget />

    @stack('scripts')
</body>

</html>