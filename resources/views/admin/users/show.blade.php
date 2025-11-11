@extends('layouts.admin')

@section('title','Admin — User Detail')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-gray-900">Detail User</h1>
      <p class="text-gray-600">Riwayat donasi pengguna.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50">← Kembali</a>
  </div>

  <div class="grid gap-4 md:grid-cols-3">
    <div class="md:col-span-2 rounded-2xl ring-1 ring-gray-200 bg-white p-5">
      <div class="text-sm text-gray-600 mb-3">
        Menampilkan {{ $donations->count() }} dari {{ $donations->total() }} donasi
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="p-3 text-left">Waktu</th>
              <th class="p-3 text-left">Campaign</th>
              <th class="p-3 text-right">Nominal</th>
              <th class="p-3 text-left">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($donations as $d)
              <tr>
                <td class="p-3 text-gray-600">{{ $d->created_at->format('d M Y H:i') }}</td>
                <td class="p-3">
                  <a class="text-blue-700 hover:text-blue-900" target="_blank"
                     href="{{ route('campaign.show', $d->campaign->slug) }}">{{ $d->campaign->title ?? '—' }}</a>
                </td>
                <td class="p-3 text-right font-semibold">Rp {{ number_format((int)$d->amount,0,',','.') }}</td>
                <td class="p-3">
                  <span class="inline-flex px-2 py-1 rounded-lg text-xs ring-1 ring-gray-200 bg-gray-50">
                    {{ $d->status }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="p-6 text-center text-gray-500">Belum ada donasi.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $donations->links() }}</div>
    </div>

    <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 space-y-2">
      <div class="text-sm text-gray-500">Nama</div>
      <div class="font-semibold text-gray-900">{{ $u->name }}</div>
      <div class="text-sm text-gray-500 mt-3">Email</div>
      <div class="text-gray-900">{{ $u->email }}</div>
      <div class="text-sm text-gray-500 mt-3">Total Settlement</div>
      <div class="text-gray-900 font-bold">Rp {{ number_format($totalSettlement,0,',','.') }}</div>
      <div class="text-sm text-gray-500 mt-3">Transaksi Settlement</div>
      <div class="text-gray-900 font-semibold">{{ number_format($countSettlement) }}</div>
    </div>
  </div>
</div>
@endsection
