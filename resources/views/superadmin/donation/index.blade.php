@extends('layouts.superadmin')

@section('title', 'Donasi')

@section('content')
<style>[x-cloak]{display:none!important}</style>

@php
  // Fallback aman kalau route tidak mengirim variabel
  $q    = $q    ?? request('q', '');
  $sort = $sort ?? request('sort', 'latest');

  // Jika $campaigns tidak ada, jadikan koleksi kosong agar @forelse aman
  $list = isset($campaigns) ? $campaigns : collect();

  // Helper untuk cek apakah $campaigns punya pagination links
  $hasPagination = isset($campaigns) && method_exists($campaigns, 'links');
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-white via-blue-50/40 to-white">
  <div class="max-w-7xl mx-auto px-6 pt-20 pb-10 relative">
    <div class="text-center">
      <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900">
        Mari <span class="text-blue-600">Berbagi Kebaikan</span> Bersama
      </h1>
      <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
        Pilih campaign yang menginspirasi Anda dan bantu mereka mewujudkan harapan.
      </p>
    </div>
  </div>
</section>

<div x-data="donationsPage" x-init="init(@js(request()->all()))" class="max-w-7xl mx-auto px-6 pt-6 pb-12">
  <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-4 md:p-6 shadow-sm flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Semua Campaign</h2>
      <p class="text-gray-500 text-sm">Gunakan pencarian untuk menemukan program tertentu.</p>
    </div>

    <form method="get" class="flex flex-wrap items-center gap-2">
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul atau kata kunci…"
             class="w-56 md:w-64 border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none"/>

      <select name="sort" class="border rounded-xl px-3 py-2 text-sm text-gray-700">
        <option value="latest"      @selected($sort==='latest')>Terbaru</option>
        <option value="target_desc" @selected($sort==='target_desc')>Target Tertinggi</option>
        <option value="target_asc"  @selected($sort==='target_asc')>Target Terendah</option>
      </select>

      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Terapkan</button>
    </form>
  </div>

  {{-- ALERT hasil pencarian --}}
  @if (!empty($q))
    <div class="mt-4 text-sm text-gray-600">
      Menampilkan hasil pencarian untuk: <span class="font-semibold text-blue-700">"{{ $q }}"</span>
    </div>
  @endif

  {{-- GRID --}}
  <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($list as $c)
      <x-campaign-card :campaign="$c" />
    @empty
      <div class="col-span-full text-center text-gray-600 bg-white ring-1 ring-gray-200 rounded-2xl p-8">
        <div class="text-5xl mb-2">🔍</div>
        <h3 class="text-lg font-semibold">Tidak ada hasil ditemukan</h3>
        <p class="text-sm text-gray-500 mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
      </div>
    @endforelse
  </div>

  {{-- PAGINATION (tampilkan hanya jika tersedia) --}}
  @if ($hasPagination)
    <div class="mt-10">
      {{ $campaigns->withQueryString()->links() }}
    </div>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('donationsPage', () => ({
    state: { q:'', status:'', sort:'latest' },
    init(qs){
      this.state.q = qs?.q ?? '';
      this.state.status = qs?.status ?? '';
      this.state.sort = qs?.sort ?? 'latest';
    }
  }));
});
</script>
@endpush
@endsection
