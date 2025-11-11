@extends('layouts.app')

@section('title', 'Instruksi Pembayaran Donasi')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
  @if (session('ok'))
    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 ring-1 ring-emerald-200">
      {{ session('ok') }}
    </div>
  @endif

  <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-6 shadow-sm">
    <h1 class="text-2xl font-bold text-gray-900">Instruksi Pembayaran</h1>
    <p class="text-gray-600 mt-1">
      Campaign: <a href="{{ route('campaign.show', $campaign) }}" class="text-blue-700 hover:underline">{{ $campaign->title }}</a>
    </p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
      <div class="rounded-xl bg-gray-50 p-4">
        <div class="text-sm text-gray-500">Nominal</div>
        <div class="text-xl font-bold">Rp {{ number_format($donation->amount,0,',','.') }}</div>
      </div>
      <div class="rounded-xl bg-gray-50 p-4">
        <div class="text-sm text-gray-500">Kode Pembayaran</div>
        <div class="text-xl font-mono font-semibold">{{ $donation->payment_ref ?: '—' }}</div>
      </div>
    </div>

    <div class="mt-6">
      <h2 class="text-sm font-semibold text-gray-700">Langkah Pembayaran</h2>
      <div class="mt-2 rounded-xl ring-1 ring-gray-200 bg-white p-4">
        @php
          $guide = $donation->payload['guide'] ?? 'Transfer ke VA 8888 00 123456 a.n. Yayasan Demo. Nominal harus tepat.';
          $expire = $donation->payload['expire'] ?? null;
        @endphp
        <p class="text-gray-700">{{ $guide }}</p>
        @if($expire)
          <p class="text-xs text-gray-500 mt-2">Berlaku hingga: {{ \Carbon\Carbon::parse($expire)->format('d M Y H:i') }}</p>
        @endif
      </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3">
      @if($donation->status === 'pending')
        <form method="post" action="{{ route('donation.confirm', $donation) }}">
          @csrf
          <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Saya sudah bayar</button>
        </form>
        <form method="post" action="{{ route('donation.cancel', $donation) }}">
          @csrf
          <button class="px-4 py-2 rounded-xl ring-1 ring-gray-200 text-gray-700 hover:bg-gray-50">Batalkan</button>
        </form>
      @elseif($donation->status === 'settlement')
        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200">
          Pembayaran berhasil ✔
        </span>
      @elseif(in_array($donation->status, ['expire','cancel']))
        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-red-100 text-red-700 ring-1 ring-red-200">
          Transaksi {{ $donation->status }}
        </span>
      @endif

      <a href="{{ route('campaign.show', $campaign) }}" class="ml-auto text-blue-700 hover:underline">Kembali ke campaign</a>
    </div>
  </div>
</div>
@endsection
