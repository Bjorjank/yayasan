@props([
  'campaign',     // App\Models\Campaign
  'cover' => null // URL cover opsional; jika null, di-resolve otomatis
])

@php
  $c = $campaign;
  $coverUrl = $cover;
  if (!$coverUrl && $c->cover_url) {
    $coverUrl = \Illuminate\Support\Str::startsWith($c->cover_url, ['http://','https://','//'])
      ? $c->cover_url
      : asset('storage/'.$c->cover_url);
  }
  $goal = (int) $c->target_amount;
  $collected = (int) $c->donations()->where('status','settlement')->sum('amount');
  $pct = $goal > 0 ? min(100, (int) floor($collected * 100 / $goal)) : 0;

  $badge = [
    'draft' => 'bg-gray-100 text-gray-700 ring-gray-200',
    'published' => 'bg-green-100 text-green-800 ring-green-200',
    'closed' => 'bg-amber-100 text-amber-800 ring-amber-200',
  ][$c->status] ?? 'bg-gray-100 text-gray-700 ring-gray-200';
@endphp

<div class="group overflow-hidden rounded-2xl ring-1 ring-gray-200 bg-white hover:shadow-md transition">
  <a href="{{ route('campaign.show', $c) }}" class="block relative">
    @if ($coverUrl)
      <img src="{{ $coverUrl }}" alt="{{ $c->title }}"
           class="w-full h-40 object-cover">
    @else
      <div class="w-full h-40 bg-gradient-to-br from-blue-50 to-indigo-50"></div>
    @endif
    <span class="absolute top-3 left-3 inline-flex px-2.5 py-1 rounded-full text-xs ring-1 {{ $badge }}">
      {{ strtoupper($c->status) }}
    </span>
  </a>

  <div class="p-4">
    <a href="{{ route('campaign.show', $c) }}" class="block">
      <h3 class="text-base font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-700">
        {{ $c->title }}
      </h3>
    </a>
    <div class="mt-1 text-xs text-gray-500">{{ $c->category ?: 'Program Donasi' }}</div>

    <div class="mt-3">
      <div class="flex items-end justify-between">
        <div class="text-sm">
          <div class="text-gray-500">Terkumpul</div>
          <div class="font-semibold">Rp {{ number_format($collected,0,',','.') }}</div>
        </div>
        <div class="text-right text-sm">
          <div class="text-gray-500">Target</div>
          <div class="font-semibold">Rp {{ number_format($goal,0,',','.') }}</div>
        </div>
      </div>
      <div class="mt-2 h-2 w-full rounded-full bg-gray-100 overflow-hidden">
        <div class="h-full bg-blue-600" style="width: {{ $pct }}%"></div>
      </div>
      <div class="mt-1 text-right text-xs text-gray-500">{{ $pct }}%</div>
    </div>

    <div class="mt-4 flex items-center justify-between">
      <a href="{{ route('campaign.show', $c) }}"
         class="text-sm text-blue-700 hover:text-blue-900">Lihat</a>
      @if ($c->status === 'published')
        <a href="{{ route('campaign.show', $c) }}"
           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">
          Donasi
        </a>
      @else
        <span class="text-xs text-amber-700 bg-amber-50 ring-1 ring-amber-200 px-2 py-1 rounded">Tutup</span>
      @endif
    </div>
  </div>
</div>
