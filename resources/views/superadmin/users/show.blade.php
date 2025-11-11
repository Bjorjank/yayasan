@extends('layouts.superadmin')

@section('title', 'User Detail')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div class="space-y-6">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-gray-900">{{ $u->name }}</h1>
      <div class="text-gray-600">{{ $u->email }}</div>
      <div class="mt-1">
        @php $role = $u->getRoleNames()->first() ?? '—'; @endphp
        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs ring-1 ring-gray-200 bg-gray-50 text-gray-700">
          {{ $role }}
        </span>
      </div>
    </div>
    <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">Kembali</a>
  </div>

  {{-- Stat --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">Total Donasi (settlement)</div>
      <div class="mt-1 text-2xl font-bold">Rp {{ number_format($totalSettlement,0,',','.') }}</div>
    </div>
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">Jumlah Transaksi (settlement)</div>
      <div class="mt-1 text-2xl font-bold">{{ number_format($countSettlement) }}</div>
    </div>
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">Bergabung</div>
      <div class="mt-1 text-2xl font-bold">{{ $u->created_at->format('d M Y') }}</div>
    </div>
  </div>

  {{-- Tabel transaksi --}}
  <div class="rounded-3xl overflow-hidden ring-1 ring-gray-200 bg-white shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100 text-sm text-gray-600">
      Riwayat transaksi donasi (semua status)
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="p-3 text-left">Waktu</th>
            <th class="p-3 text-left">Campaign</th>
            <th class="p-3 text-right">Nominal</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Order ID</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($donations as $d)
            @php $slug = $d->campaign->slug ?? null; @endphp
            <tr class="hover:bg-gray-50/60">
              <td class="p-3 text-gray-600">{{ $d->paid_at?->format('d M Y H:i') ?? $d->created_at->format('d M Y H:i') }}</td>
              <td class="p-3">
                @if($slug)
                  <a href="{{ route('campaign.show', ['campaign'=>$slug]) }}" target="_blank" class="text-blue-700 hover:text-blue-900">
                    {{ $d->campaign->title ?? '—' }}
                  </a>
                @else
                  <span class="text-gray-700">{{ $d->campaign->title ?? '—' }}</span>
                @endif
              </td>
              <td class="p-3 text-right font-semibold text-gray-900">Rp {{ number_format((int)$d->amount,0,',','.') }}</td>
              <td class="p-3">
                <span class="inline-flex px-2 py-1 rounded-lg text-xs ring-1 ring-gray-200
                  @class([
                    'bg-green-50 text-green-700' => $d->status==='settlement',
                    'bg-amber-50 text-amber-700' => $d->status==='pending',
                    'bg-gray-50 text-gray-700'   => !in_array($d->status,['settlement','pending']),
                  ])">
                  {{ $d->status }}
                </span>
              </td>
              <td class="p-3 text-gray-700 text-xs">{{ $d->order_id ?? '—' }}</td>
            </tr>
          @empty
            <tr><td class="p-6 text-center text-gray-500" colspan="5">Belum ada transaksi.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-5 py-4 border-t border-gray-100">
      {{ $donations->links() }}
    </div>
  </div>
</div>
@endsection
