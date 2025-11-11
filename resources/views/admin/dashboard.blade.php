{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title','Admin — Dashboard')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
  <div>
    <h1 class="text-2xl md:text-3xl font-black text-gray-900">Overview</h1>
    <p class="text-gray-600">Ringkasan performa platform donasi.</p>
  </div>
  <div class="flex items-center gap-2">
    {{-- Tombol aman: menuju halaman kampanye admin & donasi publik --}}
    <a href="{{ route('admin.campaigns.index') }}"
       class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
      Kelola Campaign
    </a>
    <a href="{{ route('donation') }}"
       class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">
      Lihat Halaman Donasi
    </a>
  </div>
</div>

{{-- Kartu metrik --}}
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
    <div class="text-xs text-gray-500">Total Campaign</div>
    <div class="mt-1 text-2xl font-bold">{{ number_format($totalCampaigns ?? 0) }}</div>
    <div class="mt-2 text-xs text-gray-500">Published:
      <span class="font-medium text-gray-700">{{ $published ?? 0 }}</span>,
      Closed: <span class="font-medium text-gray-700">{{ $closed ?? 0 }}</span>
    </div>
  </div>

  <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
    <div class="text-xs text-gray-500">Total Donasi (settlement)</div>
    <div class="mt-1 text-2xl font-bold">Rp {{ number_format(($sumDonations ?? 0),0,',','.') }}</div>
    <div class="mt-2 text-xs text-gray-500">Transaksi:
      <span class="font-medium text-gray-700">{{ number_format($totalDonations ?? 0) }}</span>
    </div>
  </div>

  <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
    <div class="text-xs text-gray-500">Pengguna</div>
    <div class="mt-1 text-2xl font-bold">{{ number_format($usersTotal ?? 0) }}</div>
    <div class="mt-2 text-xs text-gray-500">Total akun terdaftar</div>
  </div>

  <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
    <div class="text-xs text-gray-500">Tingkat Publikasi</div>
    @php
      $tc = (int)($totalCampaigns ?? 0);
      $pp = $tc > 0 ? floor((($published ?? 0)/$tc)*100) : 0;
    @endphp
    <div class="mt-1 text-2xl font-bold">{{ $pp }}%</div>
    <div class="mt-2 h-2 rounded-full bg-gray-100 overflow-hidden">
      <div class="h-2 bg-blue-600" style="width: {{ $pp }}%"></div>
    </div>
  </div>
</div>

{{-- Konten tambahan (demo) --}}
<div class="mt-6 grid gap-6 lg:grid-cols-3">
  <div class="lg:col-span-2 rounded-3xl bg-white/80 ring-1 ring-gray-200 p-6 shadow-sm">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-semibold text-gray-900">Aktivitas Terkini</h3>
      <span class="text-xs text-gray-500">Demo</span>
    </div>
    <ul class="mt-4 space-y-3">
      <li class="flex items-center gap-3">
        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
        <span class="text-sm text-gray-700">Donasi baru terverifikasi.</span>
      </li>
      <li class="flex items-center gap-3">
        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
        <span class="text-sm text-gray-700">Campaign “Bantu Anak Sekolah” dipublikasikan.</span>
      </li>
      <li class="flex items-center gap-3">
        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
        <span class="text-sm text-gray-700">Target campaign mendekati 75%.</span>
      </li>
    </ul>
  </div>

  <div class="rounded-3xl bg-white/80 ring-1 ring-gray-200 p-6 shadow-sm">
    <h3 class="text-sm font-semibold text-gray-900">Aksi Cepat</h3>
    <div class="mt-4 space-y-3">
      <a href="{{ route('admin.campaigns.index') }}"
         class="flex items-center justify-between px-4 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700">
        Kelola Campaign
        <span class="text-xl leading-none">→</span>
      </a>
      <a href="{{ route('donation') }}"
         class="flex items-center justify-between px-4 py-3 rounded-2xl ring-1 ring-gray-200 hover:bg-gray-50">
        Lihat Donasi Publik
        <span>↗</span>
      </a>
    </div>
  </div>
</div>
@endsection
