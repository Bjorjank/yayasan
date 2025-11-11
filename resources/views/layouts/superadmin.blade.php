<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title','Superadmin Dashboard')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @stack('scripts')
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen bg-gradient-to-b from-white via-blue-50/30 to-white text-gray-800">
<div x-data="{ sidebarOpen:false }" class="min-h-screen">

  {{-- Topbar sticky --}}
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <button class="md:hidden inline-flex items-center justify-center p-2 rounded-lg hover:bg-gray-100"
                @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Menu">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/>
            <path x-show="sidebarOpen"  stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2">
          <div class="h-9 w-9 rounded-xl bg-blue-600 text-white grid place-items-center font-bold">SA</div>
          <span class="font-semibold tracking-wide">Super<span class="text-blue-600">Admin</span></span>
        </a>
      </div>

      <div class="hidden md:flex items-center gap-3">
        <div class="relative">
          <input type="text" placeholder="Search…"
                 class="pl-9 pr-3 py-2 rounded-xl ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500/40 outline-none text-sm bg-white/70">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
            <path d="M10 4a6 6 0 104.472 10.15l3.69 3.69 1.414-1.415-3.69-3.69A6 6 0 0010 4z"/>
          </svg>
        </div>
        <div class="h-9 w-9 rounded-full bg-white ring-1 ring-gray-200 grid place-items-center">🔔</div>
        <!-- <div class="h-9 w-9 overflow-hidden rounded-full ring-1 ring-gray-200">
          <img src="{{ asset('images/avatar-default.png') }}" class="h-full w-full object-cover" alt="avatar">
        </div> -->
      </div>
    </div>
  </header>

  {{-- WRAPPER: container tengah dengan grid 2 kolom fixed --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- 🧩 KUNCI PERBAIKAN: pakai grid template kolom eksplisit --}}
    <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-6 items-start">

      {{-- SIDEBAR (desktop static kiri, mobile jadi drawer) --}}
      <aside class="w-full">
        {{-- Desktop --}}
        <div class="hidden md:block sticky top-[96px]">
          @include('superadmin.partials.sidebar')
        </div>

        {{-- Mobile Drawer --}}
        <div class="md:hidden">
          <div class="fixed inset-0 z-40 bg-black/20" x-show="sidebarOpen" x-transition.opacity
               @click="sidebarOpen=false" style="display:none"></div>

          <div class="fixed z-50 top-0 left-0 h-full w-80 max-w-[85%] p-4"
               x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-6 opacity-0"
               x-transition:enter-end="translate-x-0 opacity-100"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="translate-x-0 opacity-100"
               x-transition:leave-end="-translate-x-6 opacity-0"
               style="display:none">
            @include('superadmin.partials.sidebar')
          </div>
        </div>
      </aside>

      {{-- MAIN --}}
      <main class="w-full">
        @yield('content')
      </main>
    </div>
  </div>
</div>
</body>
</html>
