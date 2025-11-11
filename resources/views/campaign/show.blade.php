{{-- resources/views/campaign/show.blade.php --}}
@extends('layouts.app')

@section('title', $c->title)

@section('content')
<style>[x-cloak]{display:none!important}</style>
@php
  $cover = $c->cover_url
    ? (\Illuminate\Support\Str::startsWith($c->cover_url, ['http://','https://','//'])
        ? $c->cover_url
        : asset('storage/'.$c->cover_url))
    : null;

  $goal      = (int) $c->target_amount;
  $collected = (int) $sum;
  $pct       = $goal > 0 ? min(100, floor($collected * 100 / $goal)) : 0;

  // Ambil 10 donasi settlement terbaru
  $recent = $c->donations()->where('status','settlement')->latest()->limit(10)->get();
@endphp

<div x-data="donationPage" x-init="init(@js(session('ok')), @js(session('payinfo')))">

  {{-- Toast sederhana --}}
  <div class="fixed bottom-4 right-4 z-50 space-y-3" x-cloak x-show="toasts.length">
    <template x-for="t in toasts" :key="t.id">
      <div x-transition class="max-w-sm rounded-xl ring-1 ring-black/10 shadow-lg p-4 bg-white flex gap-3">
        <div class="inline-flex h-8 w-8 items-center justify-center rounded-full"
             :class="t.type==='success' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
          <span x-text="t.type==='success' ? '✓' : 'i'"></span>
        </div>
        <div class="text-sm text-gray-800" x-text="t.message"></div>
        <button class="ml-auto text-gray-400 hover:text-gray-600" @click="dismiss(t.id)">✕</button>
      </div>
    </template>
  </div>

  <div class="min-h-screen bg-white">
    {{-- Hero --}}
    <section class="relative">
      @if ($cover)
        <div class="h-64 md:h-80 w-full overflow-hidden">
          <img src="{{ $cover }}" alt="{{ $c->title }}" class="w-full h-full object-cover">
        </div>
      @else
        <div class="h-64 md:h-80 w-full bg-gradient-to-br from-blue-50 to-indigo-50"></div>
      @endif

      <div class="max-w-5xl mx-auto px-6 -mt-10 md:-mt-16 relative z-10">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-black/5 p-6 md:p-8">
          <div class="flex items-start gap-4">
            @if ($cover)
              <img src="{{ $cover }}" alt="thumb" class="hidden md:block h-20 w-20 rounded object-cover ring-1 ring-gray-200">
            @endif
            <div class="flex-1">
              <div class="text-xs text-gray-500 uppercase tracking-wider">
                {{ $c->category ?: 'Program Donasi' }}
              </div>
              <h1 class="mt-1 text-2xl md:text-3xl font-bold text-gray-900">
                {{ $c->title }}
              </h1>
              <div class="mt-2 flex items-center gap-3 text-sm">
                <span class="inline-flex px-2.5 py-1 rounded-full ring-1
                  @class([
                    'bg-gray-100 text-gray-700 ring-gray-200' => $c->status==='draft',
                    'bg-green-100 text-green-800 ring-green-200' => $c->status==='published',
                    'bg-amber-100 text-amber-800 ring-amber-200' => $c->status==='closed',
                  ])">
                  {{ strtoupper($c->status) }}
                </span>
                @if ($c->deadline_at)
                  <span class="text-gray-500">Batas: {{ $c->deadline_at->format('d M Y H:i') }}</span>
                @endif
              </div>
            </div>
          </div>

          {{-- Progress --}}
          <div class="mt-6">
            <div class="flex items-end justify-between">
              <div>
                <div class="text-sm text-gray-600">Terkumpul</div>
                <div class="text-xl font-semibold">
                  Rp {{ number_format($collected,0,',','.') }}
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-gray-600">Target</div>
                <div class="text-xl font-semibold">
                  Rp {{ number_format($goal,0,',','.') }}
                </div>
              </div>
            </div>
            <div class="mt-3 h-3 w-full rounded-full bg-gray-100 overflow-hidden">
              <div class="h-full bg-blue-600" style="width: {{ $pct }}%"></div>
            </div>
            <div class="mt-1 text-right text-xs text-gray-500">{{ $pct }}%</div>
          </div>

          {{-- Form Donasi (langsung di halaman campaign) --}}
          @if ($c->status === 'published')
            <form class="mt-6" method="post" action="{{ route('donation.create', $c) }}" @submit="beforeSubmit">
              @csrf
              <div class="grid md:grid-cols-3 gap-4">
                <div>
                  <label class="text-sm font-medium text-gray-700">Nama (opsional)</label>
                  <input name="donor_name" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"
                         placeholder="Hamba ALLAH (default)">
                  <p class="mt-1 text-xs text-gray-500">Kosongkan untuk tampil sebagai <strong>Hamba ALLAH</strong>.</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-700">Email (opsional)</label>
                  <input type="email" name="donor_email" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"
                         placeholder="nama@email.com">
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-700">WhatsApp (opsional)</label>
                  <input type="tel" name="donor_whatsapp" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm"
                         placeholder="62xxxxxxxxxxx">
                  <p class="mt-1 text-xs text-gray-500">Contoh: 62812xxxxxxx (angka saja).</p>
                </div>
              </div>

              <div class="mt-4 grid md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                  <label class="text-sm font-medium text-gray-700">Nominal (Rp)</label>
                  <input type="text" inputmode="numeric" x-model="amountView" @input="formatAmount()"
                         class="mt-1 w-full border rounded-xl px-3 py-2 text-sm" placeholder="cth: 50.000" required>
                  <input type="hidden" name="amount" :value="amountRaw">
                  <p class="mt-1 text-xs text-gray-500">Minimal Rp 1.000</p>
                </div>

                {{-- quick-pick nominal --}}
                <div class="md:col-span-2 flex flex-wrap gap-2 items-end">
                  <button type="button" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50 text-sm"
                          @click="quick(10000)">10.000</button>
                  <button type="button" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50 text-sm"
                          @click="quick(25000)">25.000</button>
                  <button type="button" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50 text-sm"
                          @click="quick(50000)">50.000</button>
                  <button type="button" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 hover:bg-gray-50 text-sm"
                          @click="quick(100000)">100.000</button>
                  <div class="ml-auto">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21c-4.97 0-9-3.582-9-8 0-2.656 1.5-5.016 3.938-6.438a1 1 0 0 1 1.125 1.654C6.5 8.109 6 9.5 6 11c0 3.309 2.691 6 6 6s6-2.691 6-6c0-1.5-.5-2.891-1.063-3.784a1 1 0 0 1 1.125-1.654C19.5 7.984 21 10.344 21 13c0 4.418-4.03 8-9 8z"/></svg>
                      Donasi Sekarang
                    </button>
                  </div>
                </div>
              </div>
            </form>
          @else
            <div class="mt-6 text-sm text-amber-700 bg-amber-50 ring-1 ring-amber-200 px-4 py-3 rounded-xl">
              Campaign tidak dibuka untuk donasi saat ini.
            </div>
          @endif

          {{-- Instruksi pembayaran (jika ada) --}}
          @if (session('payinfo'))
            @php $pi = session('payinfo'); @endphp
            <div class="mt-6 rounded-xl bg-blue-50 ring-1 ring-blue-200 p-4">
              <div class="font-semibold text-blue-800 mb-1">Instruksi Pembayaran</div>
              <pre class="text-xs text-blue-900 overflow-x-auto">{{ json_encode($pi, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
          @endif
        </div>
      </div>
    </section>

    {{-- Deskripsi + Donasi terbaru --}}
    <section class="max-w-5xl mx-auto px-6 py-10">
      <div class="grid md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
          <h2 class="text-lg font-semibold text-gray-900 mb-3">Tentang Campaign</h2>
          <div class="prose prose-blue max-w-none">
            {!! nl2br(e($c->description ?? 'Tidak ada deskripsi.')) !!}
          </div>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-gray-900 mb-3">Donasi Terbaru</h2>
          <div class="space-y-3">
            @forelse ($recent as $d)
              <div class="rounded-xl ring-1 ring-gray-200 p-3">
                <div class="text-sm font-medium text-gray-900">
                  {{ $d->donor_name ?? 'Anonim' }}
                </div>
                <div class="text-sm text-gray-600">
                  Rp {{ number_format((int)$d->amount,0,',','.') }}
                </div>
                <div class="text-xs text-gray-500">
                  {{ optional($d->paid_at ?? $d->created_at)->diffForHumans() }}
                </div>
              </div>
            @empty
              <div class="rounded-xl ring-1 ring-gray-200 p-3 text-sm text-gray-500">
                Belum ada donasi settlement.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection

@push('meta')
  {{-- SEO/OG minimal --}}
  <meta property="og:title" content="{{ $c->title }}">
  @if ($cover)<meta property="og:image" content="{{ $cover }}">@endif
  <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($c->description), 160) }}">
  <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($c->description), 160) }}">
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('donationPage', () => ({
    // toasts
    toasts: [], tid: 0,
    push(type,msg){ const id=++this.tid; this.toasts.push({id,type,message:msg}); setTimeout(()=>this.dismiss(id), 4000); },
    dismiss(id){ this.toasts = this.toasts.filter(t => t.id !== id); },

    // amount state
    amountView: '',
    amountRaw: '',

    // lifecycle
    init(flashOk=null, payinfo=null){
      if (flashOk) this.push('success', flashOk);
      if (payinfo) this.push('info', 'Instruksi pembayaran tersedia di halaman ini.');
    },

    // helpers
    _strip(s){ return String(s||'').replace(/[^\d]/g,''); },
    _fmtDots(n){ return n.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); },

    formatAmount(){
      const raw = this._strip(this.amountView);
      this.amountRaw  = raw;
      this.amountView = this._fmtDots(raw);
    },

    quick(n){
      const s = String(n);
      this.amountRaw  = s;
      this.amountView = this._fmtDots(s);
    },

    beforeSubmit(e){
      if (!this.amountRaw || parseInt(this.amountRaw,10) < 1000){
        e.preventDefault();
        this.push('info','Minimal donasi Rp 1.000');
      }
    },
  }));
});
</script>
@endpush
