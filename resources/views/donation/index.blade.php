{{-- resources/views/donation/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Donasi')

@section('content')
<style>
  [x-cloak] {
    display: none !important
  }
</style>

{{-- HERO: latar putih, aksen bar biru+merah di atas --}}
<section class="relative overflow-hidden bg-white">
  <div class="h-[2px] w-full">
    <div class="h-[2px] w-full bg-brand-blue"></div>
    <div class="h-[2px] w-full bg-brand-red"></div>
  </div>

  <div class="max-w-7xl mx-auto px-6 pt-20 pb-10 relative">
    <div class="text-center">
      <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900">
        Mari <span class="text-brand-blue">Berbagi Kebaikan</span> Bersama
      </h1>
      <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
        Pilih campaign yang menginspirasi Anda dan bantu mereka mewujudkan harapan.
      </p>
    </div>
  </div>
</section>

<div x-data="donationsPage" x-init="init(@js(request()->all()))" class="max-w-7xl mx-auto px-6 pt-6 pb-12">
  {{-- FILTER BAR --}}
  <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-4 md:p-6 shadow-sm flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h2 class="text-xl md:text-2xl font-semibold text-gray-900 flex items-center gap-2">
        <x-heroicon-o-rectangle-stack class="w-6 h-6 text-brand-blue" />
        Semua Campaign
      </h2>
      <p class="text-gray-500 text-sm">Gunakan pencarian untuk menemukan program tertentu.</p>
    </div>

    <form method="get" class="flex flex-wrap items-center gap-2">
      {{-- Input cari --}}
      <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
          <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
        </span>
        <input
          type="text"
          name="q"
          value="{{ $q ?? '' }}"
          placeholder="Cari judul atau kata kunci…"
          class="w-56 md:w-64 border rounded-xl pl-10 pr-3 py-2 text-sm
                 focus:ring-2 focus:ring-brand-blue/40 focus:outline-none" />
      </div>

      {{-- Sort --}}
      <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
          <x-heroicon-o-funnel class="w-5 h-5 text-gray-400" />
        </span>
        <select
          name="sort"
          class="border rounded-xl pl-10 pr-8 py-2 text-sm text-gray-700
                 focus:ring-2 focus:ring-brand-blue/40 focus:outline-none">
          <option value="latest" @selected(($sort ?? '' )==='latest' )>Terbaru</option>
          <option value="target_desc" @selected(($sort ?? '' )==='target_desc' )>Target Tertinggi</option>
          <option value="target_asc" @selected(($sort ?? '' )==='target_asc' )>Target Terendah</option>
        </select>
        <span class="pointer-events-none absolute inset-y-0 right-0 pr-3 flex items-center">
          <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400" />
        </span>
      </div>

      {{-- Tombol Terapkan (aksi utama = MERAH) --}}
      <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-red text-white text-sm hover:brightness-95">
        <x-heroicon-o-adjustments-horizontal class="w-5 h-5 text-white/90" />
        Terapkan
      </button>
    </form>
  </div>

  {{-- ALERT hasil pencarian --}}
  @if (!empty($q))
  <div class="mt-4 text-sm text-gray-600 flex items-center gap-1.5">
    <x-heroicon-o-information-circle class="w-4 h-4 text-brand-blue" />
    Menampilkan hasil pencarian untuk:
    <span class="font-semibold text-brand-blue">"{{ $q }}"</span>
  </div>
  @endif

  {{-- GRID --}}
  <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($campaigns as $c)
    <x-campaign-card :campaign="$c" />
    @empty
    <div class="col-span-full text-center text-gray-600 bg-white ring-1 ring-gray-200 rounded-2xl p-8">
      <div class="mx-auto mb-3 flex items-center justify-center h-12 w-12 rounded-full bg-brand-blue/10">
        <x-heroicon-o-magnifying-glass class="w-6 h-6 text-brand-blue" />
      </div>
      <h3 class="text-lg font-semibold">Tidak ada hasil ditemukan</h3>
      <p class="text-sm text-gray-500 mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
    </div>
    @endforelse
  </div>

  {{-- PAGINATION --}}
  <div class="mt-10">
    {{ $campaigns->withQueryString()->links() }}
  </div>
</div>

<x-footer />

  @push('scripts')
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('donationsPage', () => ({
        state: {
          q: '',
          status: '',
          sort: 'latest'
        },
        init(qs) {
          this.state.q = qs?.q ?? '';
          this.state.status = qs?.status ?? '';
          this.state.sort = qs?.sort ?? 'latest';
        }
      }));
    });
  </script>
  @endpush
  @endsection