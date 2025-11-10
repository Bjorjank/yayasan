@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div x-data="contactPage" x-init="init(@js(session('ok')))">
  {{-- HERO --}}
  <section class="relative overflow-hidden bg-gradient-to-b from-white via-blue-50/30 to-white">
    <div aria-hidden="true" class="absolute -left-24 top-10 h-72 w-72 rounded-full blur-3xl opacity-30"
         style="background: radial-gradient(110px 110px at 50% 50%, #145efc55, transparent 60%);"></div>
    <div class="max-w-6xl mx-auto px-6 pt-16 pb-10">
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
          <span class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-blue-700/80">
            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span> Hubungi Kami
          </span>
          <h1 class="mt-3 text-3xl md:text-5xl font-black leading-tight text-gray-900">
            Mari Terhubung & Kolaborasi.
          </h1>
          <p class="mt-4 text-gray-600 max-w-xl">
            Ada ide, pertanyaan, atau ingin bermitra? Kirim pesan—kami senang mendengarnya.
          </p>
          <div class="mt-6 flex items-center gap-3 text-sm text-gray-600">
            <span class="inline-flex items-center gap-2"><span>📍</span> Jakarta</span>
            <span class="inline-flex items-center gap-2"><span>⏰</span> Senin–Jumat, 09:00–17:00</span>
          </div>
        </div>
        <div class="relative">
          <div class="aspect-[4/3] w-full rounded-3xl ring-1 ring-gray-200 bg-white/70 backdrop-blur-sm shadow-sm p-6 grid place-items-center">
            {{-- Map placeholder aesthetic --}}
            <div class="w-full h-full rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 grid place-items-center">
              <div class="text-center">
                <div class="text-6xl">🗺️</div>
                <div class="text-sm text-gray-500 mt-2">Map Placeholder</div>
              </div>
            </div>
          </div>
          <div class="absolute -bottom-6 -left-6 h-20 w-20 rounded-2xl bg-white ring-1 ring-gray-200 shadow grid place-items-center">
            <div class="text-center">
              <div class="text-2xl">💬</div>
              <div class="text-[10px] text-gray-500">Cepat Tanggap</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- TOAST --}}
  <div class="fixed bottom-4 right-4 z-50" x-cloak x-show="toastMsg">
    <div x-transition
         class="max-w-sm rounded-2xl ring-1 ring-black/10 bg-white p-4 shadow-lg flex gap-3">
      <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700">✓</span>
      <div class="text-sm text-gray-800" x-text="toastMsg"></div>
      <button class="ml-auto text-gray-400 hover:text-gray-600" @click="toastMsg=null">✕</button>
    </div>
  </div>

  {{-- CONTACT CARDS + FORM --}}
  <section class="py-10">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-3 gap-8">
      {{-- Cards --}}
      <div class="space-y-4">
        <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 hover:shadow-md transition">
          <div class="text-2xl">✉️</div>
          <div class="mt-2 font-semibold text-gray-900">Email</div>
          <a href="mailto:info@yayasan.org" class="text-blue-700 hover:text-blue-900 text-sm">info@yayasan.org</a>
          <p class="mt-2 text-xs text-gray-500">Balasan 1×24 jam kerja.</p>
        </div>
        <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 hover:shadow-md transition">
          <div class="text-2xl">📞</div>
          <div class="mt-2 font-semibold text-gray-900">Telepon</div>
          <div class="text-sm text-gray-700">+62 812 3456 7890</div>
          <p class="mt-2 text-xs text-gray-500">Senin–Jumat, 09:00–17:00 WIB.</p>
        </div>
        <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 hover:shadow-md transition">
          <div class="text-2xl">📍</div>
          <div class="mt-2 font-semibold text-gray-900">Alamat</div>
          <div class="text-sm text-gray-700">Jl. Contoh No. 123, Jakarta</div>
          <p class="mt-2 text-xs text-gray-500">Silakan buat janji sebelum berkunjung.</p>
        </div>
      </div>

      {{-- Form --}}
      <div class="lg:col-span-2 rounded-3xl ring-1 ring-gray-200 bg-white p-6 md:p-8">
        <form method="post" action="{{ route('contact.submit') }}" @submit="beforeSubmit">
          @csrf
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium text-gray-700">Nama</label>
              <input name="name" x-model="f.name" required
                     class="mt-1 w-full border rounded-2xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/40 focus:outline-none">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" x-model="f.email" required
                     class="mt-1 w-full border rounded-2xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/40 focus:outline-none">
            </div>
          </div>
          <div class="mt-4">
            <label class="text-sm font-medium text-gray-700">Subjek</label>
            <input name="subject" x-model="f.subject" required
                   class="mt-1 w-full border rounded-2xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/40 focus:outline-none">
          </div>
          <div class="mt-4">
            <label class="text-sm font-medium text-gray-700">Pesan</label>
            <textarea name="message" rows="6" x-model="f.message" required
                      class="mt-1 w-full border rounded-2xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/40 focus:outline-none"></textarea>
            <p class="mt-1 text-xs text-gray-500">Berikan konteks jelas agar kami dapat membantu lebih cepat.</p>
          </div>
          <div class="mt-6 flex items-center justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 rounded-2xl ring-1 ring-gray-200">Reset</button>
            <button class="px-5 py-2.5 rounded-2xl bg-blue-600 text-white hover:bg-blue-700">Kirim</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  {{-- FAQ + JAM OPERASIONAL --}}
  <section class="py-10">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-8">
      {{-- FAQ --}}
      <div>
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Pertanyaan Umum</h2>
        <div class="mt-4 divide-y divide-gray-200 rounded-2xl ring-1 ring-gray-200 bg-white">
          <details class="group p-5" x-data>
            <summary class="cursor-pointer list-none flex items-center justify-between">
              <span class="font-medium text-gray-900">Bagaimana cara berdonasi?</span>
              <span class="transition group-open:rotate-180">⌄</span>
            </summary>
            <p class="mt-3 text-sm text-gray-600">Pilih campaign, isi nominal & data, lalu selesaikan pembayaran.</p>
          </details>
          <details class="group p-5" x-data>
            <summary class="cursor-pointer list-none flex items-center justify-between">
              <span class="font-medium text-gray-900">Apakah donasi saya tercatat?</span>
              <span class="transition group-open:rotate-180">⌄</span>
            </summary>
            <p class="mt-3 text-sm text-gray-600">Ya. Anda akan menerima konfirmasi, dan progres tampil di halaman campaign.</p>
          </details>
          <details class="group p-5" x-data>
            <summary class="cursor-pointer list-none flex items-center justify-between">
              <span class="font-medium text-gray-900">Bagaimana laporan penggunaan dana?</span>
              <span class="transition group-open:rotate-180">⌄</span>
            </summary>
            <p class="mt-3 text-sm text-gray-600">Kami merilis laporan berkala untuk tiap program secara transparan.</p>
          </details>
        </div>
      </div>

      {{-- Jam Operasional --}}
      <div>
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Jam Operasional</h2>
        <div class="mt-4 grid sm:grid-cols-2 gap-4">
          @foreach ([
            ['Senin','09:00–17:00'],['Selasa','09:00–17:00'],['Rabu','09:00–17:00'],
            ['Kamis','09:00–17:00'],['Jumat','09:00–17:00'],['Sabtu','Tutup']
          ] as [$day,$time])
          <div class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 flex items-center justify-between">
            <div class="font-medium text-gray-900">{{ $day }}</div>
            <div class="text-sm text-gray-600">{{ $time }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('contactPage', () => ({
    toastMsg: null,
    f: { name:'', email:'', subject:'', message:'' },
    init(ok){ if (ok) { this.toastMsg = ok; setTimeout(()=> this.toastMsg=null, 4000); } },
    beforeSubmit(e){
      const { name, email, subject, message } = this.f;
      if(!name.trim() || !email.trim() || !subject.trim() || !message.trim()){
        e.preventDefault(); alert('Semua field wajib diisi.'); return;
      }
    }
  }));
});
</script>
@endpush
