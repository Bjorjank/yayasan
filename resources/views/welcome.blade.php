{{-- resources/views/welcome.blade.php --}}
<x-app-layout>
  <div class="bg-white">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10">
        <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full blur-3xl bg-red-600/15"></div>
        <div class="absolute -bottom-20 -left-20 h-[28rem] w-[28rem] rounded-full blur-3xl bg-blue-600/15"></div>
        <div class="absolute inset-0 bg-white"></div>
      </div>

      <div class="mx-auto max-w-7xl px-6 pt-14 pb-20 lg:pt-20 lg:pb-28">
        <div class="grid items-center gap-10 lg:grid-cols-2">
          <div>
            <span
              class="inline-flex items-center gap-2 rounded-full px-2.5 py-1.5 text-xs font-medium ring-1 ring-inset bg-red-600/10 text-red-700 ring-red-600/30">
              <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M11 7h2v6h-2V7Zm1-5a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Z" />
              </svg>
              Yayasan • Peduli & Kolaborasi
            </span>

            <h1 class="mt-5 text-4xl font-bold tracking-tight text-gray-900 md:text-5xl">
              Satu Platform <span class="text-red-600">Kebaikan</span> untuk Donasi & Kolaborasi
            </h1>

            <p class="mt-4 leading-relaxed text-gray-600">
              Bangun program sosial, ajak relawan, dan himpun donasi secara transparan. Chat langsung antar pengguna & koordinator—semua dalam satu aplikasi modern.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
              <a href="#campaigns"
                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white transition hover:brightness-95">
                Mulai Donasi
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M13 5l7 7-7 7v-5H4v-4h9V5z" />
                </svg>
              </a>

              <a href="#fitur"
                class="inline-flex items-center gap-2 rounded-xl border border-blue-600/50 px-5 py-3 text-blue-600 transition hover:bg-blue-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M12 2 9.5 8.5 3 11l6.5 2.5L12 20l2.5-6.5L21 11l-6.5-2.5L12 2z" />
                </svg>
                Lihat Fitur
              </a>
            </div>

            {{-- Stat cards --}}
            <div class="mt-10 grid max-w-lg grid-cols-3 gap-6">
              <div class="rounded-2xl bg-white p-4 ring-1 ring-gray-100 shadow-sm">
                <div class="flex items-center gap-2 text-gray-900">
                  <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M16 11c1.66 0 2.99 1.34 2.99 3L19 18H5v-4c0-1.66 1.34-3 3-3h8zM12 4a4 4 0 1 1 0 8 4 4 0 0 1 0-8z" />
                  </svg>
                  <div class="text-2xl font-bold">+12k</div>
                </div>
                <div class="text-xs text-gray-500">Donatur Berbagi</div>
              </div>

              <div class="rounded-2xl bg-white p-4 ring-1 ring-gray-100 shadow-sm">
                <div class="flex items-center gap-2 text-gray-900">
                  <svg class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M3 7h18v10H3zM5 5h14v2H5zM7 9h3v6H7zM12 9h3v6h-3zM17 9h2v6h-2z" />
                  </svg>
                  <div class="text-2xl font-bold">Rp 4,2M</div>
                </div>
                <div class="text-xs text-gray-500">Dana Tersalurkan</div>
              </div>

              <div class="rounded-2xl bg-white p-4 ring-1 ring-gray-100 shadow-sm">
                <div class="flex items-center gap-2 text-gray-900">
                  <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="m12 2 3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-7z" />
                  </svg>
                  <div class="text-2xl font-bold">180+</div>
                </div>
                <div class="text-xs text-gray-500">Program Aktif</div>
              </div>
            </div>
          </div>

          {{-- Hero image + badge --}}
          <div class="relative">
            <div class="aspect-[4/3] w-full overflow-hidden rounded-3xl ring-1 ring-gray-200 shadow-lg">
              <img
                src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop"
                alt="Hero" class="h-full w-full object-cover">
            </div>

            <div class="absolute -bottom-6 -left-6 hidden w-48 rounded-2xl bg-white p-3 ring-1 ring-gray-100 shadow md:block">
              <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M2 4h20v12H6l-4 4V4z" />
                </svg>
                Realtime Chat
              </div>
              <div class="mt-1 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                <span class="text-sm font-medium text-gray-800">Koordinator Online</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- TRUST BAR --}}
    <section class="bg-white py-10">
      <div class="mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-2 items-center gap-6 md:grid-cols-4">
          @php
          $trust = [
          ['title'=>'Terdaftar','desc'=>'Yayasan resmi','icon'=>'shield','c'=>'red'],
          ['title'=>'Transparan','desc'=>'Laporan rutin','icon'=>'chart','c'=>'blue'],
          ['title'=>'Aman','desc'=>'Pembayaran tersertifikasi','icon'=>'lock','c'=>'red'],
          ['title'=>'Mudah','desc'=>'Chat & koordinasi','icon'=>'check','c'=>'blue'],
          ];
          @endphp

          @foreach ($trust as $t)
          @php
          $chipBg = $t['c']==='red' ? 'bg-red-600/15 text-red-700' : 'bg-blue-600/15 text-blue-700';
          @endphp
          <div class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-lg {{ $chipBg }}">
              @if ($t['icon']==='shield')
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2 4 5v6c0 5 3.5 9.7 8 11 4.5-1.3 8-6 8-11V5l-8-3z" />
              </svg>
              @elseif ($t['icon']==='chart')
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 3h2v16h16v2H3zM7 13h3v6H7zm5-6h3v12h-3zm5 3h3v9h-3z" />
              </svg>
              @elseif ($t['icon']==='lock')
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2a5 5 0 0 1 5 5v3h1a2 2 0 0 1 2 2v8H4v-8a2 2 0 0 1 2-2h1V7a5 5 0 0 1 5-5zm3 8V7a3 3 0 1 0-6 0v3h6z" />
              </svg>
              @else
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="m9 16-4-4 2-2 2 2 6-6 2 2-8 8z" />
              </svg>
              @endif
            </div>
            <div>
              <div class="text-sm font-semibold">{{ $t['title'] }}</div>
              <div class="text-xs text-gray-500">{{ $t['desc'] }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- FITUR UTAMA --}}
    <section id="fitur" class="bg-red-600/10 py-16">
      <div class="mx-auto max-w-7xl px-6">
        <div class="max-w-2xl">
          <h2 class="text-2xl font-bold text-gray-900 md:text-3xl">Semua yang Anda butuhkan</h2>
          <p class="mt-2 text-gray-600">Kelola program, chat, dan donasi dalam satu atap—ringkas dan efisien.</p>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
          @php
          $features = [
          ['title'=>'Program Donasi','desc'=>'Buat program, target, tenggat & pantau progresnya.','icon'=>'heart','c'=>'red'],
          ['title'=>'Chat Realtime','desc'=>'DM ala WhatsApp untuk relawan & donatur.','icon'=>'chat','c'=>'blue'],
          ['title'=>'Pembayaran Aman','desc'=>'Gateway lokal—otomatis & akurat.','icon'=>'card','c'=>'red'],
          ];
          @endphp

          @foreach ($features as $f)
          @php
          $chip = $f['c']==='red' ? 'bg-red-600/20 text-red-700' : 'bg-blue-600/20 text-blue-700';
          @endphp
          <div class="rounded-2xl bg-white p-6 ring-1 ring-gray-100 shadow-sm transition hover:shadow-md">
            <div class="grid h-11 w-11 place-items-center rounded-xl {{ $chip }}">
              @if ($f['icon']==='heart')
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z" />
              </svg>
              @elseif ($f['icon']==='chat')
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M2 4h20v12H6l-4 4V4z" />
              </svg>
              @else
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 7h18v10H3zM5 5h14v2H5z" />
              </svg>
              @endif
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $f['title'] }}</h3>
            <p class="mt-2 text-gray-600">{{ $f['desc'] }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- KAMPANYE PILIHAN (dummy) --}}
    <section id="campaigns" class="bg-white py-16">
      <div class="mx-auto max-w-7xl px-6">
        <div class="flex items-end justify-between gap-6">
          <div>
            <h2 class="text-2xl font-bold text-gray-900 md:text-3xl">Kampanye Pilihan</h2>
            <p class="mt-2 text-gray-600">Contoh kartu program (demo image).</p>
          </div>
          <a href="{{ route('home') }}" class="font-medium text-blue-600 hover:opacity-90">Lihat semua</a>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ([1,2,3] as $i)
          <div class="rounded-2xl overflow-hidden bg-white ring-1 ring-gray-100 shadow-sm transition hover:shadow-md">
            <div class="aspect-[16/10] w-full overflow-hidden">
              <img
                src="https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?q=80&w=1600&auto=format&fit=crop"
                alt="Campaign {{ $i }}" class="h-full w-full object-cover">
            </div>
            <div class="p-5">
              <h3 class="line-clamp-2 font-semibold text-gray-900">Bantu Pendidikan Anak Pelosok #{{ $i }}</h3>
              <p class="mt-1 line-clamp-2 text-sm text-gray-600">Penggalangan dana untuk akses buku, seragam, dan beasiswa.</p>

              <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>Terkumpul</span><span>Rp {{ number_format(32000000 + $i*1000000,0,',','.') }}</span>
                </div>
                <div class="mt-1 h-2 w-full rounded-full bg-gray-100">
                  <div class="h-2 rounded-full bg-red-600" style="width: {{ 55 + $i*5 }}%"></div>
                </div>
              </div>

              <div class="mt-5 flex items-center gap-3">
                <a href="{{ route('home') }}"
                  class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm text-white hover:brightness-95">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="m12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z" />
                  </svg>
                  Donasi
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:opacity-90">
                  Detail
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M13 5l7 7-7 7M5 12h14" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- TESTIMONI (Alpine optional) --}}
    <section class="bg-blue-600/10 py-16"
      x-data="{i:0, items:[
        {q:'Platformnya mudah dipakai & transparan. Laporan jelas!', n:'Amira – Donatur'},
        {q:'Koordinasi relawan jadi rapi berkat fitur chat realtime.', n:'Bima – Koordinator'},
        {q:'Proses donasi cepat dan aman. Sangat membantu!', n:'Sari – Donatur'}
      ]}">
      <div class="mx-auto max-w-4xl px-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 md:text-3xl">Apa kata mereka</h2>
        <div class="relative mt-8">
          <div class="rounded-2xl bg-white p-8 ring-1 ring-gray-100 shadow-sm md:p-10">
            <svg class="mx-auto h-8 w-8 text-red-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M2 4h9v10H6l-4 4V4Zm13 0h9v10h-5l-4 4V4Z" />
            </svg>
            <p class="mt-4 text-lg text-gray-700" x-text="items[i]?.q || '…'">…</p>
            <div class="mt-4 text-sm font-medium text-blue-600" x-text="items[i]?.n || ''"></div>
          </div>
          <div class="mt-4 flex items-center justify-center gap-2">
            <template x-for="(it, idx) in items" :key="idx">
              <button class="h-2.5 w-2.5 rounded-full"
                :class="idx===i ? 'bg-blue-600' : 'bg-blue-300'"
                @click="i=idx"></button>
            </template>
          </div>
        </div>
      </div>
    </section>

    {{-- CTA --}}
    <section class="bg-white py-14">
      <div class="mx-auto max-w-7xl px-6">
        <div class="relative overflow-hidden rounded-3xl ring-1 ring-red-600/30 bg-red-600">
          <div class="absolute -right-10 -top-10 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>

          <div class="grid items-center gap-8 px-8 py-10 text-white md:grid-cols-2 md:px-14 md:py-14">
            <div>
              <h3 class="text-2xl font-bold md:text-3xl">Siap bergabung dalam gerakan kebaikan?</h3>
              <p class="mt-2 text-white/90">Buat program, undang relawan, dan mulai mengubah dunia kecilmu hari ini.</p>
            </div>

            <div class="flex gap-3 md:justify-end">
              <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 font-medium text-blue-600 hover:bg-gray-50">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15 14a5 5 0 1 1-6 0V9a3 3 0 1 1 6 0v5z" />
                </svg>
                Daftar Gratis
              </a>
              <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-white ring-1 ring-white/70 hover:bg-white/10">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M10 17 5 12l5-5v3h6v4h-6v3z" />
                </svg>
                Masuk
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

  <x-footer />
</x-app-layout>