{{-- resources/views/welcome.blade.php --}}
<x-app-layout>
  <div class="bg-white">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10">
        {{-- glow solid (tanpa gradient) --}}
        <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-brand-red/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 h-[28rem] w-[28rem] rounded-full bg-brand-blue/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-white"></div>
      </div>

      <div class="max-w-7xl mx-auto px-6 pt-14 pb-20 lg:pt-20 lg:pb-28">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
          <div>
            <span
              class="inline-flex items-center gap-2 text-xs font-medium px-2.5 py-1.5 rounded-full ring-1 ring-inset bg-brand-red/10 text-brand-red ring-brand-red/25">
              <x-heroicon-s-information-circle class="h-3.5 w-3.5" />
              Yayasan • Peduli & Kolaborasi
            </span>

            <h1 class="mt-5 text-4xl md:text-5xl font-bold tracking-tight text-gray-900">
              Satu Platform <span class="text-brand-red">Kebaikan</span> untuk Donasi & Kolaborasi
            </h1>

            <p class="mt-4 text-gray-600 leading-relaxed">
              Bangun program sosial, ajak relawan, dan himpun donasi secara transparan. Chat langsung antar pengguna & koordinator—semua dalam satu aplikasi yang modern.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
              {{-- Tombol primer (MERAH) --}}
              <a href="#campaigns"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-white transition bg-brand-red hover:brightness-95">
                Mulai Donasi
                <x-heroicon-s-arrow-right class="h-4 w-4" />
              </a>

              {{-- Tombol sekunder (outline BIRU) --}}
              <a href="#fitur"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border transition border-brand-blue/40 text-brand-blue hover:bg-brand-blue/10">
                <x-heroicon-s-sparkles class="h-4 w-4" />
                Lihat Fitur
              </a>
            </div>

            {{-- Stat cards --}}
            <div class="mt-10 grid grid-cols-3 gap-6 max-w-lg">
              <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4">
                <div class="flex items-center gap-2 text-gray-900">
                  <x-heroicon-s-user-group class="h-5 w-5 text-brand-blue" />
                  <div class="text-2xl font-bold">+12k</div>
                </div>
                <div class="text-xs text-gray-500">Donatur Berbagi</div>
              </div>
              <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4">
                <div class="flex items-center gap-2 text-gray-900">
                  <x-heroicon-s-banknotes class="h-5 w-5 text-brand-red" />
                  <div class="text-2xl font-bold">Rp 4,2M</div>
                </div>
                <div class="text-xs text-gray-500">Dana Tersalurkan</div>
              </div>
              <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4">
                <div class="flex items-center gap-2 text-gray-900">
                  <x-heroicon-s-rocket-launch class="h-5 w-5 text-brand-blue" />
                  <div class="text-2xl font-bold">180+</div>
                </div>
                <div class="text-xs text-gray-500">Program Aktif</div>
              </div>
            </div>
          </div>

          {{-- Hero image + badge --}}
          <div class="relative">
            <div class="aspect-[4/3] w-full rounded-3xl overflow-hidden ring-1 ring-gray-200 shadow-lg">
              <img
                src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop"
                alt="Hero"
                class="h-full w-full object-cover">
            </div>

            <div class="hidden md:block absolute -bottom-6 -left-6 w-48 rounded-2xl bg-white shadow ring-1 ring-gray-100 p-3">
              <div class="flex items-center gap-2 text-xs text-gray-500">
                <x-heroicon-s-chat-bubble-left-right class="h-4 w-4 text-brand-blue" />
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

    {{-- TRUST BAR (ikon merah & biru bergantian) --}}
    <section class="py-10 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 items-center">
          @php
          $trust = [
          ['title'=>'Terdaftar','desc'=>'Yayasan resmi','icon'=>'shield-check','c'=>'red'],
          ['title'=>'Transparan','desc'=>'Laporan rutin','icon'=>'chart-bar','c'=>'blue'],
          ['title'=>'Aman','desc'=>'Pembayaran tersertifikasi','icon'=>'lock-closed','c'=>'red'],
          ['title'=>'Mudah','desc'=>'Chat & koordinasi','icon'=>'clipboard-document-check','c'=>'blue'],
          ];
          @endphp

          @foreach ($trust as $t)
          @php
          $chip = $t['c']==='red' ? 'bg-brand-red/10 text-brand-red' : 'bg-brand-blue/10 text-brand-blue';
          @endphp
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-lg grid place-items-center {{ $chip }}">
              @switch($t['icon'])
              @case('shield-check') <x-heroicon-s-shield-check class="h-5 w-5" /> @break
              @case('chart-bar') <x-heroicon-s-chart-bar class="h-5 w-5" /> @break
              @case('lock-closed') <x-heroicon-s-lock-closed class="h-5 w-5" /> @break
              @case('clipboard-document-check') <x-heroicon-s-clipboard-document-check class="h-5 w-5" /> @break
              @endswitch
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

    {{-- FITUR UTAMA (latar merah tipis) --}}
    <section id="fitur" class="py-16 bg-brand-red/10">
      <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-2xl">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Semua yang Anda butuhkan</h2>
          <p class="mt-2 text-gray-600">Kelola program, chat, dan donasi dalam satu atap—ringkas dan efisien.</p>
        </div>

        <div class="mt-10 grid md:grid-cols-3 gap-6">
          @php
          $features = [
          ['title'=>'Program Donasi','desc'=>'Buat program, target, tenggat & pantau progresnya.','icon'=>'heart','c'=>'red'],
          ['title'=>'Chat Realtime','desc'=>'DM ala WhatsApp untuk relawan & donatur.','icon'=>'chat','c'=>'blue'],
          ['title'=>'Pembayaran Aman','desc'=>'Gateway lokal—otomatis & akurat.','icon'=>'credit-card','c'=>'red'],
          ];
          @endphp

          @foreach ($features as $f)
          @php
          $chip = $f['c']==='red' ? 'bg-brand-red/15 text-brand-red' : 'bg-brand-blue/15 text-brand-blue';
          @endphp
          <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-6 hover:shadow-md transition">
            <div class="h-11 w-11 rounded-xl grid place-items-center {{ $chip }}">
              @switch($f['icon'])
              @case('heart') <x-heroicon-s-heart class="h-5 w-5" /> @break
              @case('chat') <x-heroicon-s-chat-bubble-left-right class="h-5 w-5" /> @break
              @case('credit-card') <x-heroicon-s-credit-card class="h-5 w-5" /> @break
              @endswitch
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $f['title'] }}</h3>
            <p class="mt-2 text-gray-600">{{ $f['desc'] }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- KAMPANYE PILIHAN --}}
    <section id="campaigns" class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between gap-6">
          <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Kampanye Pilihan</h2>
            <p class="mt-2 text-gray-600">Contoh kartu program (demo image).</p>
          </div>
          <a href="{{ route('home') }}" class="font-medium text-brand-blue hover:brightness-90">
            Lihat semua
          </a>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach ([1,2,3] as $i)
          <div class="rounded-2xl overflow-hidden bg-white ring-1 ring-gray-100 shadow-sm hover:shadow-md transition">
            <div class="aspect-[16/10] w-full overflow-hidden">
              <img
                src="https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?q=80&w=1600&auto=format&fit=crop"
                alt="Campaign {{ $i }}" class="h-full w-full object-cover">
            </div>
            <div class="p-5">
              <h3 class="font-semibold text-gray-900 line-clamp-2">Bantu Pendidikan Anak Pelosok #{{ $i }}</h3>
              <p class="mt-1 text-sm text-gray-600 line-clamp-2">Penggalangan dana untuk akses buku, seragam, dan beasiswa.</p>

              <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>Terkumpul</span><span>Rp {{ number_format(32000000 + $i*1000000,0,',','.') }}</span>
                </div>
                <div class="mt-1 h-2 w-full rounded-full bg-gray-100">
                  <div class="h-2 rounded-full bg-brand-red" style="width: {{ 55 + $i*5 }}%"></div>
                </div>
              </div>

              <div class="mt-5 flex items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-red text-white text-sm hover:brightness-95">
                  <x-heroicon-s-heart class="h-4 w-4" />
                  Donasi
                </a>
                <a href="{{ route('home') }}" class="text-sm text-brand-blue hover:brightness-90 inline-flex items-center gap-1">
                  Detail
                  <x-heroicon-s-arrow-right class="h-4 w-4" />
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- TESTIMONI --}}
    <section class="py-16 bg-brand-blue/10"
      x-data="{i:0, items:[
               {q:'Platformnya mudah dipakai & transparan. Laporan jelas!', n:'Amira – Donatur'},
               {q:'Koordinasi relawan jadi rapi berkat fitur chat realtime.', n:'Bima – Koordinator'},
               {q:'Proses donasi cepat dan aman. Sangat membantu!', n:'Sari – Donatur'}
             ]}">
      <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Apa kata mereka</h2>
        <div class="mt-8 relative">
          <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-8 md:p-10">
            <x-heroicon-s-chat-bubble-left-right class="mx-auto h-8 w-8 text-brand-red" />
            <p class="mt-4 text-lg text-gray-700" x-text="items[i].q"></p>
            <div class="mt-4 text-sm font-medium text-brand-blue" x-text="items[i].n"></div>
          </div>
          <div class="mt-4 flex items-center justify-center gap-2">
            <template x-for="(it, idx) in items" :key="idx">
              <button class="h-2.5 w-2.5 rounded-full"
                :class="idx===i? 'bg-brand-blue':'bg-brand-blue/30'"
                @click="i=idx"></button>
            </template>
          </div>
        </div>
      </div>
    </section>

    {{-- CTA (solid MERAH) --}}
    <section class="py-14 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <div class="relative overflow-hidden rounded-3xl ring-1 ring-brand-red/30 bg-brand-red">
          <div class="absolute -right-10 -top-10 h-56 w-56 bg-white/10 rounded-full blur-2xl"></div>

          <div class="px-8 py-10 md:px-14 md:py-14 text-white grid md:grid-cols-2 gap-8 items-center">
            <div>
              <h3 class="text-2xl md:text-3xl font-bold">Siap bergabung dalam gerakan kebaikan?</h3>
              <p class="mt-2 text-white/90">Buat program, undang relawan, dan mulai mengubah dunia kecilmu hari ini.</p>
            </div>

            <div class="flex md:justify-end gap-3">
              <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-medium text-brand-blue bg-white hover:bg-gray-50">
                <x-heroicon-s-user-plus class="h-5 w-5" />
                Daftar Gratis
              </a>
              <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-white ring-1 ring-white/70 hover:bg-white/10">
                <x-heroicon-s-arrow-right-on-rectangle class="h-5 w-5" />
                Masuk
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

  {{-- footer global --}}
  <x-footer />
</x-app-layout>