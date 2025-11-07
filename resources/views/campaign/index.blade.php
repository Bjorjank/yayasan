@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6">Program Donasi</h1>

    @if ($campaigns->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
            Belum ada campaign yang dipublikasikan.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($campaigns as $c)
                <div class="bg-white rounded-lg shadow border">
                    @if ($c->cover_url)
                        <img src="{{ $c->cover_url }}" alt="{{ $c->title }}" class="w-full h-40 object-cover rounded-t-lg">
                    @endif
                    <div class="p-4">
                        <h2 class="text-lg font-semibold mb-1">
                            <a href="{{ route('campaign.show', $c->slug) }}" class="hover:underline">
                                {{ $c->title }}
                            </a>
                        </h2>
                        <div class="text-sm text-gray-500 mb-3">
                            Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}
                        </div>

                        @php
                            $collected = (int) $c->donations()->where('status','settlement')->sum('amount');
                            $target = max(1, (int) $c->target_amount);
                            $pct = min(100, round(($collected / $target) * 100, 2));
                        @endphp
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="text-sm text-gray-600 mb-4">
                            Terkumpul: Rp {{ number_format($collected, 0, ',', '.') }} ({{ $pct }}%)
                        </div>

                        <a href="{{ route('campaign.show', $c->slug) }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>


@endsection
