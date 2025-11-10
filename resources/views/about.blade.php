@extends('layouts.app')

@section('title', 'Tentang Yayasan')

@section('content')
<style>[x-cloak]{display:none!important}</style>

{{-- HERO AESTHETIC --}}
<section class="relative overflow-hidden bg-gradient-to-b from-white via-blue-50/40 to-white">
  <div aria-hidden="true" class="absolute -top-24 -right-20 h-80 w-80 rounded-full blur-3xl opacity-30"
       style="background: radial-gradient(120px 120px at 50% 50%, #145efc55, transparent 60%);"></div>
  <div class="max-w-6xl mx-auto px-6 pt-16 pb-10">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <span class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-blue-700/80">
          <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span> Tentang Kami
        </span>
        <h1 class="mt-3 text-3xl md:text-5xl font-black leading-tight text-gray-900">
          Menyalakan Kebaikan,<br class="hidden md:block"> Mewujudkan Dampak.
        </h1>
        <p class="mt-4 text-gray-600 max-w-xl">
          Kami menghubungkan donatur dengan program yang tepat sasaran—transparan, akuntabel, dan berkelanjutan.
          Fokus kami: pendidikan, kesehatan, dan kemandirian ekonomi.
        </p>
        <div class="mt-6 flex items-center gap-3">
          <a href="{{ url('/donations') }}"
             class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700">
            Lihat Program
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5 12h12l-4-4 1.4-1.4L21.8 12l-7.4 5.4L13 16l4-4H5z"/></svg>
          </a>
          <a href="{{ url('/contact') }}" class="px-5 py-3 rounded-2xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">
            Hubungi Kami
          </a>
        </div>
      </div>
      <div class="relative">
        <div class="aspect-[4/3] w-full rounded-3xl ring-1 ring-gray-200 bg-white/70 backdrop-blur-sm shadow-sm p-6 grid place-items-center">
          {{-- Placeholder ilustrasi aesthetic --}}
          <svg viewBox="0 0 320 240" class="w-full h-full text-blue-600/40">
            <defs>
              <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#145efc"/>
                <stop offset="100%" stop-color="#60a5fa"/>
              </linearGradient>
            </defs>
            <rect x="0" y="0" width="320" height="240" rx="18" fill="url(#g1)" opacity="0.09"/>
            <g fill="currentColor" opacity="0.55">
              <circle cx="70" cy="120" r="20"/>
              <rect x="110" y="100" width="130" height="40" rx="10"/>
              <rect x="60" y="170" width="200" height="10" rx="5" opacity="0.6"/>
              <rect x="60" y="190" width="160" height="10" rx="5" opacity="0.35"/>
            </g>
          </svg>
        </div>
        <div class="absolute -bottom-6 -right-6 h-20 w-20 rounded-2xl bg-white ring-1 ring-gray-200 shadow grid place-items-center">
          <div class="text-center">
            <div class="text-2xl">🤝</div>
            <div class="text-[10px] text-gray-500">Kolaborasi</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- NILAI INTI --}}
<section class="py-12">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Nilai yang Kami Pegang</h2>
    <p class="mt-2 text-gray-600 max-w-2xl">Tiga pilar yang menuntun setiap langkah kami.</p>

    <div class="mt-6 grid md:grid-cols-3 gap-6">
      <div class="rounded-2xl bg-white ring-1 ring-gray-200 p-6 hover:shadow-md transition">
        <div class="text-3xl">🔍</div>
        <h3 class="mt-3 font-semibold text-gray-900">Transparansi</h3>
        <p class="mt-2 text-sm text-gray-600">Laporan rutin, penggunaan dana yang jelas, dan akses informasi publik.</p>
      </div>
      <div class="rounded-2xl bg-white ring-1 ring-gray-200 p-6 hover:shadow-md transition">
        <div class="text-3xl">🎯</div>
        <h3 class="mt-3 font-semibold text-gray-900">Dampak Nyata</h3>
        <p class="mt-2 text-sm text-gray-600">Program terukur dengan indikator keberhasilan yang realistis.</p>
      </div>
      <div class="rounded-2xl bg-white ring-1 ring-gray-200 p-6 hover:shadow-md transition">
        <div class="text-3xl">🤝</div>
        <h3 class="mt-3 font-semibold text-gray-900">Kolaborasi</h3>
        <p class="mt-2 text-sm text-gray-600">Jaringan mitra, relawan, dan komunitas untuk memperluas jangkauan.</p>
      </div>
    </div>
  </div>
</section>

{{-- STATISTIK / IMPACT --}}
<section class="py-10">
  <div class="max-w-6xl mx-auto px-6">
    <div class="rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-600 p-8 md:p-12 text-white">
      <div class="grid md:grid-cols-4 gap-8 text-center">
        <div>
          <div class="text-4xl font-black">120+</div>
          <div class="mt-1 text-sm/relaxed text-white/90">Siswa Terbantu</div>
        </div>
        <div>
          <div class="text-4xl font-black">35</div>
          <div class="mt-1 text-sm/relaxed text-white/90">Beasiswa Aktif</div>
        </div>
        <div>
          <div class="text-4xl font-black">24</div>
          <div class="mt-1 text-sm/relaxed text-white/90">Klinik Kesehatan</div>
        </div>
        <div>
          <div class="text-4xl font-black">98%</div>
          <div class="mt-1 text-sm/relaxed text-white/90">Akurasi Penyaluran</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- TIM (DEMO) --}}
<section class="py-12">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Tim Penggerak</h2>
    <p class="mt-2 text-gray-600 max-w-2xl">Tim kecil, lincah, dan berdedikasi tinggi.</p>

    <div class="mt-6 grid sm:grid-cols-2 md:grid-cols-3 gap-6">
      @foreach (['Rani','Dwi','Galih','Maya','Andi','Sari'] as $name)
      <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 hover:shadow-md transition">
        <div class="flex items-center gap-4">
          <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 ring-1 ring-gray-200 grid place-items-center">
            <span class="text-lg font-semibold text-blue-700">{{ substr($name,0,1) }}</span>
          </div>
          <div>
            <div class="font-semibold text-gray-900">{{ $name }}</div>
            <div class="text-xs text-gray-500">Program Manager</div>
          </div>
        </div>
        <p class="mt-3 text-sm text-gray-600">Mendorong inovasi program dan memastikan dampak maksimal.</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="py-12">
  <div class="max-w-6xl mx-auto px-6">
    <div class="rounded-3xl ring-1 ring-gray-200 bg-white p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <h3 class="text-xl md:text-2xl font-bold text-gray-900">Siap ikut bergerak?</h3>
        <p class="text-gray-600">Dukung program kami atau ajak kolaborasi untuk dampak yang lebih luas.</p>
      </div>
      <div class="flex gap-3">
        <a href="{{ url('/donations') }}" class="px-5 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700">Donasi Sekarang</a>
        <a href="{{ url('/contact') }}" class="px-5 py-3 rounded-2xl ring-1 ring-gray-200 hover:bg-gray-50">Hubungi Kami</a>
      </div>
    </div>
  </div>
</section>
@endsection
