{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white">

  {{-- HERO --}}
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10">
      <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>
      <div class="absolute -bottom-20 -left-20 h-[28rem] w-[28rem] rounded-full bg-blue-300/20 blur-3xl"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-white via-white to-blue-50/60"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pt-14 pb-20 lg:pt-20 lg:pb-28">
      <div class="grid lg:grid-cols-2 gap-10 items-center">
        <div>
          <span class="inline-flex items-center gap-2 text-xs font-medium px-2.5 py-1.5 rounded-full bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20Zm1 14h-2v-2h2v2Zm0-4h-2V6h2v6Z"/></svg>
            Yayasan • Peduli & Kolaborasi
          </span>
          <h1 class="mt-5 text-4xl md:text-5xl font-bold tracking-tight text-gray-900">
            Satu Platform <span class="text-blue-600">Kebaikan</span> untuk Donasi & Kolaborasi
          </h1>
          <p class="mt-4 text-gray-600 leading-relaxed">
            Bangun program sosial, ajak relawan, dan himpun donasi secara transparan. Chat langsung antar pengguna & koordinator—semua dalam satu aplikasi yang modern.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#campaigns" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
              Mulai Donasi
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13 5l7 7-7 7M5 12h14"/></svg>
            </a>
            <a href="#fitur" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-blue-200 text-blue-700 hover:bg-blue-50 transition">
              Lihat Fitur
            </a>
          </div>

          <div class="mt-10 grid grid-cols-3 gap-6 max-w-lg">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4">
              <div class="text-2xl font-bold text-gray-900">+12k</div>
              <div class="text-xs text-gray-500">Donatur Berbagi</div>
            </div>
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4">
              <div class="text-2xl font-bold text-gray-900">Rp 4,2M</div>
              <div class="text-xs text-gray-500">Dana Tersalurkan</div>
            </div>
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4">
              <div class="text-2xl font-bold text-gray-900">180+</div>
              <div class="text-xs text-gray-500">Program Aktif</div>
            </div>
          </div>
        </div>

        <div class="relative">
            <div class="aspect-[4/3] w-full rounded-3xl overflow-hidden ring-1 ring-gray-200 shadow-lg relative bg-gradient-to-br from-blue-50 to-white">
            <picture>
                {{-- Desktop first (Pexels yang stabil) --}}
                <source media="(min-width: 1024px)" srcset="https://images.pexels.com/photos/6646910/pexels-photo-6646910.jpeg?auto=compress&cs=tinysrgb&w=2000&h=1500&dpr=1">
                {{-- Mobile/tablet default (Pexels lebih kecil) --}}
                <img
                id="hero-img"
                src="https://images.pexels.com/photos/6646910/pexels-photo-6646910.jpeg?auto=compress&cs=tinysrgb&w=1200&h=900&dpr=1"
                alt="Ilustrasi kegiatan sosial yayasan"
                class="h-full w-full object-cover"
                loading="eager"
                referrerpolicy="no-referrer"
                crossorigin="anonymous"
                data-fallbacks='[
                    "https://images.unsplash.com/photo-1603575449292-21e60cdef7dd?auto=format&fit=crop&w=2000&q=80",
                    "https://images.unsplash.com/photo-1520975916090-3105956dac38?auto=format&fit=crop&w=2000&q=80",
                    "https://placehold.co/2000x1500/145efc/ffffff?text=Yayasan+%E2%80%93+Hero"
                ]'
                >
            </picture>

            {{-- Loader tipis di bawah agar tidak blank saat ganti fallback --}}
            <div id="hero-loader" class="absolute inset-x-0 bottom-0 h-0.5 bg-blue-600/30 animate-pulse"></div>
            </div>


          <div class="hidden md:block absolute -bottom-6 -left-6 w-40 rounded-2xl bg-white shadow ring-1 ring-gray-100 p-3">
            <div class="text-xs text-gray-500">Realtime Chat</div>
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
  <section class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 items-center">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l7 4v6c0 5-3.5 9.74-7 10-3.5-.26-7-5-7-10V6l7-4z"/></svg>
          </div>
          <div>
            <div class="text-sm font-semibold">Terdaftar</div>
            <div class="text-xs text-gray-500">Yayasan resmi</div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12l2-2 4 4L19 4l2 2-12 12z"/></svg>
          </div>
          <div>
            <div class="text-sm font-semibold">Transparan</div>
            <div class="text-xs text-gray-500">Laporan rutin</div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
          </div>
          <div>
            <div class="text-sm font-semibold">Aman</div>
            <div class="text-xs text-gray-500">Pembayaran tersertifikasi</div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5L22 5"/></svg>
          </div>
          <div>
            <div class="text-sm font-semibold">Mudah</div>
            <div class="text-xs text-gray-500">Chat & koordinasi</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FITUR UTAMA --}}
  <section id="fitur" class="py-16 bg-blue-50/60">
    <div class="max-w-7xl mx-auto px-6">
      <div class="max-w-2xl">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Semua yang Anda butuhkan</h2>
        <p class="mt-2 text-gray-600">Kelola program, chat, dan donasi dalam satu atap—ringkas dan efisien.</p>
      </div>
      <div class="mt-10 grid md:grid-cols-3 gap-6">
        @php
          $features = [
            ['title' => 'Program Donasi', 'desc' => 'Buat program, target, tenggat & pantau progresnya.', 'icon' => 'M12 2l7 4v6c0 5-3.5 9.74-7 10-3.5-.26-7-5-7-10V6l7-4z'],
            ['title' => 'Chat Realtime', 'desc' => 'DM ala WhatsApp untuk relawan & donatur.', 'icon' => 'M3 12l2-2 4 4L19 4l2 2-12 12z'],
            ['title' => 'Pembayaran Aman', 'desc' => 'Payment gateway lokal—otomatis & akurat.', 'icon' => 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z'],
          ];
        @endphp
        @foreach ($features as $f)
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
          <div class="h-11 w-11 rounded-xl bg-blue-100 text-blue-600 grid place-items-center">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $f['icon'] }}"/></svg>
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
        <a href="{{ route('home') }}" class="text-blue-700 hover:text-blue-800 font-medium">Lihat semua</a>
      </div>

      <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ([1,2,3] as $i)
        <div class="rounded-2xl overflow-hidden bg-white ring-1 ring-gray-100 shadow-sm hover:shadow-md transition">
          <div class="aspect-[16/10] w-full overflow-hidden">
            <img
              src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1600&auto=format&fit=crop"
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
                <div class="h-2 rounded-full bg-blue-600" style="width: {{ 55 + $i*5 }}%"></div>
              </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
              <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Donasi</a>
              <a href="{{ route('home') }}" class="text-sm text-blue-700 hover:text-blue-800">Detail</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- TESTIMONI (mini slider Alpine) --}}
  <section class="py-16 bg-blue-50/60" x-data="{i:0, items:[
    {q:'Platformnya mudah dipakai & transparan. Laporan jelas!', n:'Amira – Donatur'},
    {q:'Koordinasi relawan jadi rapi berkat fitur chat realtime.', n:'Bima – Koordinator'},
    {q:'Proses donasi cepat dan aman. Sangat membantu!', n:'Sari – Donatur'}
  ]}">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Apa kata mereka</h2>
      <div class="mt-8 relative">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-8 md:p-10">
          <svg class="mx-auto h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M7 7h4v10H5V9h2V7zm10 0h4v10h-6V9h2V7z"/></svg>
          <p class="mt-4 text-lg text-gray-700" x-text="items[i].q"></p>
          <div class="mt-4 text-sm font-medium text-blue-700" x-text="items[i].n"></div>
        </div>
        <div class="mt-4 flex items-center justify-center gap-2">
          <template x-for="(it, idx) in items" :key="idx">
            <button class="h-2.5 w-2.5 rounded-full"
                    :class="idx===i? 'bg-blue-600':'bg-blue-200'"
                    @click="i=idx"></button>
          </template>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="relative overflow-hidden rounded-3xl ring-1 ring-blue-200 bg-gradient-to-r from-blue-600 to-blue-500">
        <div class="absolute -right-10 -top-10 h-56 w-56 bg-white/10 rounded-full blur-2xl"></div>
        <div class="px-8 py-10 md:px-14 md:py-14 text-white grid md:grid-cols-2 gap-8 items-center">
          <div>
            <h3 class="text-2xl md:text-3xl font-bold">Siap bergabung dalam gerakan kebaikan?</h3>
            <p class="mt-2 text-white/90">Buat program, undang relawan, dan mulai mengubah dunia kecilmu hari ini.</p>
          </div>
          <div class="flex md:justify-end gap-3">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-blue-700 font-medium hover:bg-blue-50">Daftar Gratis</a>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl ring-1 ring-white/60 text-white hover:bg-white/10">Masuk</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FOOTER --}}
  <footer class="py-10 bg-blue-50/60">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-8">
        <div>
          <div class="text-lg font-bold text-gray-900">YayasanKita</div>
          <p class="mt-2 text-sm text-gray-600">Platform kolaborasi kebaikan. Donasi aman, program transparan, chat realtime.</p>
        </div>
        <div>
          <div class="text-sm font-semibold text-gray-900">Produk</div>
          <ul class="mt-3 space-y-2 text-sm text-gray-600">
            <li><a href="#fitur" class="hover:text-gray-900">Fitur</a></li>
            <li><a href="#campaigns" class="hover:text-gray-900">Kampanye</a></li>
            <li><a href="#" class="hover:text-gray-900">Harga (soon)</a></li>
          </ul>
        </div>
        <div>
          <div class="text-sm font-semibold text-gray-900">Bantuan</div>
          <ul class="mt-3 space-y-2 text-sm text-gray-600">
            <li><a href="#" class="hover:text-gray-900">FAQ</a></li>
            <li><a href="#" class="hover:text-gray-900">Kebijakan</a></li>
            <li><a href="#" class="hover:text-gray-900">Kontak</a></li>
          </ul>
        </div>
        <div>
          <div class="text-sm font-semibold text-gray-900">Ikuti Kami</div>
          <div class="mt-3 flex gap-3">
            <a href="#" class="h-9 w-9 grid place-items-center rounded-full bg-white ring-1 ring-gray-200 text-gray-700 hover:text-blue-600">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.75c-.77.34-1.6.57-2.47.67a4.25 4.25 0 001.86-2.35 8.48 8.48 0 01-2.69 1.03A4.24 4.24 0 0015.5 4c-2.37 0-4.29 1.92-4.29 4.29 0 .34.04.67.11.99A12.02 12.02 0 013 5.15a4.29 4.29 0 001.32 5.72 4.21 4.21 0 01-1.95-.54v.05c0 2.06 1.47 3.77 3.41 4.16a4.3 4.3 0 01-1.12.15c-.27 0-.55-.03-.81-.08a4.3 4.3 0 004 2.98A8.52 8.52 0 013 19.54a12.03 12.03 0 006.51 1.91c7.81 0 12.09-6.47 12.09-12.09 0-.18 0-.36-.01-.53A8.6 8.6 0 0022 5.75z"/></svg>
            </a>
            <a href="#" class="h-9 w-9 grid place-items-center rounded-full bg-white ring-1 ring-gray-200 text-gray-700 hover:text-blue-600">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.04c-5.5 0-9.96 4.45-9.96 9.96 0 4.41 3.58 8.06 8.23 8.9v-6.29H7.9v-2.61h2.37V9.41c0-2.35 1.4-3.65 3.53-3.65 1.02 0 2.09.18 2.09.18v2.3H14.7c-1.24 0-1.62.77-1.62 1.56v1.87h2.76l-.44 2.61h-2.32v6.29c4.65-.84 8.23-4.49 8.23-8.9 0-5.51-4.46-9.96-9.96-9.96z"/></svg>
            </a>
          </div>
        </div>
      </div>
      <div class="mt-8 border-t border-blue-100 pt-6 text-xs text-gray-500">&copy; {{ date('Y') }} YayasanKita. All rights reserved.</div>
    </div>
  </footer>

</div>
<script>
            (function(){
            const img = document.getElementById('hero-img');
            if(!img) return;
            let idx = 0;
            const list = (() => {
                try { return JSON.parse(img.getAttribute('data-fallbacks') || '[]'); }
                catch { return []; }
            })();

            img.addEventListener('error', () => {
                if (idx < list.length) {
                img.src = list[idx++];
                } else {
                // terakhir: jadikan background gradient saja
                img.remove();
                }
            }, { once:false });

            img.addEventListener('load', () => {
                const bar = document.getElementById('hero-loader');
                if (bar) bar.remove();
            });
            })();
            </script>

@endsection
