{{-- resources/views/components/campaign-card.blade.php --}}
@props([
'campaign', // App\Models\Campaign
'cover' => null // URL cover opsional; jika null, di-resolve otomatis
])

@php
/** @var \App\Models\Campaign $c */
$c = $campaign;

// Resolve cover
$coverUrl = $cover;
if (!$coverUrl && $c->cover_url) {
$coverUrl = \Illuminate\Support\Str::startsWith($c->cover_url, ['http://','https://','//'])
? $c->cover_url
: asset('storage/'.$c->cover_url);
}

// Progress
$goal = (int) $c->target_amount;
$collected = (int) $c->donations()->where('status','settlement')->sum('amount');
$pct = $goal > 0 ? min(100, (int) floor($collected * 100 / $goal)) : 0;

// Badge status (gunakan biru/merah agar selaras brand)
$badgeClass = match ($c->status) {
'published' => 'bg-brand-blue/10 text-brand-blue ring-brand-blue/20',
'closed' => 'bg-brand-red/10 text-brand-red ring-brand-red/20',
default => 'bg-gray-100 text-gray-700 ring-gray-200',
};

// Kategori fallback
$categoryLabel = $c->category ?: 'Program Donasi';
@endphp

<div class="group overflow-hidden rounded-2xl ring-1 ring-gray-200 bg-white hover:shadow-md transition">
  <a href="{{ route('campaign.show', $c) }}" class="block relative">
    @if ($coverUrl)
    <img src="{{ $coverUrl }}" alt="{{ $c->title }}"
      class="w-full h-40 object-cover">
    @else
    {{-- Fallback cover: strip biru + merah, tanpa gradient agar konsisten brand --}}
    <div class="w-full h-40 relative bg-white">
      <div class="absolute top-0 left-0 w-full h-1 bg-brand-blue"></div>
      <div class="absolute top-2 left-0 w-full h-[3px] bg-brand-red"></div>
      <div class="absolute inset-0 grid place-items-center text-sm text-gray-400">
        <div class="flex items-center gap-2">
          <x-heroicon-o-photo class="w-5 h-5" />
          <span>Belum ada gambar</span>
        </div>
      </div>
    </div>
    @endif

    {{-- STATUS BADGE --}}
    <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium ring-1 {{ $badgeClass }}">
      @if($c->status === 'published')
      <x-heroicon-o-bolt class="w-4 h-4" />
      @elseif($c->status === 'closed')
      <x-heroicon-o-no-symbol class="w-4 h-4" />
      @else
      <x-heroicon-o-clock class="w-4 h-4" />
      @endif
      {{ strtoupper($c->status) }}
    </span>
  </a>

  <div class="p-4">
    {{-- TITLE --}}
    <a href="{{ route('campaign.show', $c) }}" class="block">
      <h3 class="text-base font-semibold text-gray-900 line-clamp-2 group-hover:text-brand-blue">
        {{ $c->title }}
      </h3>
    </a>
    <div class="mt-1 text-xs text-gray-500 flex items-center gap-1.5">
      <x-heroicon-o-tag class="w-4 h-4 text-brand-blue/80" />
      {{ $categoryLabel }}
    </div>

    {{-- NUMBERS --}}
    <div class="mt-3">
      <div class="flex items-end justify-between">
        <div class="text-sm">
          <div class="text-gray-500 flex items-center gap-1.5">
            <x-heroicon-o-currency-dollar class="w-4 h-4 text-brand-blue/80" />
            Terkumpul
          </div>
          <div class="font-semibold">Rp {{ number_format($collected,0,',','.') }}</div>
        </div>
        <div class="text-right text-sm">
          <div class="text-gray-500 flex items-center gap-1.5 justify-end">
            <x-heroicon-o-flag class="w-4 h-4 text-brand-red/80" />
            Target
          </div>
          <div class="font-semibold">Rp {{ number_format($goal,0,',','.') }}</div>
        </div>
      </div>

      {{-- PROGRESS (solid biru) --}}
      <div class="mt-2 h-2 w-full rounded-full bg-gray-100 overflow-hidden">
        <div class="h-full bg-brand-blue transition-all" style="width: {{ $pct }}%"></div>
      </div>
      <div class="mt-1 text-right text-xs text-gray-500">{{ $pct }}%</div>
    </div>

    {{-- ACTIONS --}}
    <div class="mt-4 flex items-center justify-between">
      <a href="{{ route('campaign.show', $c) }}"
        class="text-sm text-brand-blue hover:text-brand-blue/90 inline-flex items-center gap-1">
        <x-heroicon-o-eye class="w-4 h-4" />
        Lihat
      </a>

      @if ($c->status === 'published')
      <a href="{{ route('campaign.show', $c) }}"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-red text-white text-sm hover:brightness-95">
        <x-heroicon-o-heart class="w-4 h-4 text-white/90" />
        Donasi
      </a>
      @else
      <span class="text-xs text-amber-700 bg-amber-50 ring-1 ring-amber-200 px-2 py-1 rounded inline-flex items-center gap-1.5">
        <x-heroicon-o-pause-circle class="w-4 h-4" />
        Tutup
      </span>
      @endif
    </div>
  </div>
</div>