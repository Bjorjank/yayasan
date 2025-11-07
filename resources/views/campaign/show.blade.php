@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('home') }}" class="text-blue-600 hover:underline">&larr; Kembali</a>

    <div class="mt-4 bg-white rounded-lg shadow border overflow-hidden">
        @if ($c->cover_url)
            <img src="{{ $c->cover_url }}" alt="{{ $c->title }}" class="w-full h-56 object-cover">
        @endif

        <div class="p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $c->title }}</h1>

            @php
                $target = max(1, (int) $c->target_amount);
                $pct = min(100, round(($sum / $target) * 100, 2));
            @endphp

            <div class="text-sm text-gray-600 mb-2">
                Target: <b>Rp {{ number_format($c->target_amount, 0, ',', '.') }}</b>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
            <div class="text-sm text-gray-600 mb-4">
                Terkumpul: <b>Rp {{ number_format($sum, 0, ',', '.') }}</b> ({{ $pct }}%)
            </div>

            <div class="prose max-w-none mb-6">
                {!! nl2br(e($c->description)) !!}
            </div>

            @auth
                <form method="POST" action="{{ route('donation.create', $c) }}" class="bg-gray-50 border rounded p-4">
                    @csrf
                    <label class="block text-sm font-medium mb-1">Nominal Donasi</label>
                    <div class="flex gap-2">
                        <input type="number" name="amount" min="1000" step="1000" required
                               class="border rounded px-3 py-2 w-full"
                               placeholder="Contoh: 50000">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Donasi Sekarang
                        </button>
                    </div>
                    @error('amount')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </form>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    Silakan <a href="{{ route('login') }}" class="underline">login</a> untuk berdonasi.
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
