{{-- resources/views/front/contact.blade.php --}}
<x-app-layout>
    <section class="relative overflow-hidden bg-white">
        {{-- BACKDROP HALUS DAN KONSISTEN --}}
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-28 -right-28 h-[28rem] w-[28rem] rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-[24rem] w-[24rem] rounded-full bg-blue-300/10 blur-3xl"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white via-white to-blue-50/40"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 sm:py-12 lg:py-16">
            {{-- HEADER (TERSUSUN RAPI) --}}
            <header class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10 items-start">
                <div class="lg:col-span-2">
                    <p class="text-xs sm:text-sm font-medium tracking-wide text-blue-700/80 uppercase">Hubungi Kami</p>
                    <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight text-gray-900">
                        Mari kolaborasi untuk <span class="text-blue-700">kebaikan</span>
                    </h1>
                    <p class="mt-3 sm:mt-4 text-gray-600 text-base sm:text-lg leading-relaxed max-w-3xl">
                        Ada pertanyaan, saran program, atau kemitraan? Tim kami siap membantu. Rata-rata balasan 1–3 jam kerja.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#form" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm sm:text-base font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
                            </svg>
                            Kirim Pesan
                        </a>
                        <a href="#map" class="inline-flex items-center gap-2 rounded-xl ring-1 ring-gray-200 bg-white px-5 py-2.5 text-sm sm:text-base text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z" />
                            </svg>
                            Lihat Lokasi
                        </a>
                    </div>
                </div>

                {{-- ILLUSTRATION (SEJAJAR & SUDUT MODERAT, TANPA BULGE BERLEBIHAN) --}}
                <div class="lg:col-span-1 lg:self-start">
                    <div class="relative mx-auto w-full max-w-[560px] overflow-hidden rounded-lg ring-1 ring-gray-200 shadow-sm">
                        <img
                            src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1600&auto=format&fit=crop"
                            alt="Tim layanan yayasan"
                            class="h-full w-full object-cover"
                            loading="lazy">
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/25 via-black/0 to-black/0"></div>
                        <div class="absolute bottom-3 left-3 rounded-md bg-white/95 backdrop-blur px-3 py-1.5 text-xs sm:text-sm text-gray-800 ring-1 ring-gray-200">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span> Online pada jam kerja
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- 3 KARTU INFORMASI — KONSISTEN DI SEMUA BREAKPOINT --}}
            <section class="mt-8 sm:mt-10 lg:mt-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v1l-9 6L3 6V5z" />
                                    <path d="M3 8l9 6 9-6v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">Email</div>
                                <div class="text-sm sm:text-base text-gray-600">halo@yayasan.test</div>
                                <div class="mt-2 text-xs text-gray-500">Balasan ± 1–3 jam kerja</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M6.62 10.79a15.1 15.1 0 006.59 6.59l1.82-1.82a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1C10.2 21 3 13.8 3 5a1 1 0 011-1h2.11a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1 1 0 01-.24 1.02l-1.82 1.82z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">Telepon (jam kerja)</div>
                                <div class="text-sm sm:text-base text-gray-600">+62 21 1234 5678</div>
                                <div class="mt-2 text-xs text-gray-500">Senin–Jumat 09.00–17.00 WIB</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 10.5C3 6.36 6.36 3 10.5 3S18 6.36 18 10.5 14.64 18 10.5 18A7.5 7.5 0 013 10.5zm7.5-5.5a5.5 5.5 0 105.5 5.5A5.5 5.5 0 0010.5 5z" />
                                    <path d="M21 21l-4.35-4.35" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">Alamat</div>
                                <div class="text-sm sm:text-base text-gray-600">Jl. Contoh Sejahtera No. 10, Jakarta</div>
                                <div class="mt-2 text-xs text-gray-500">Kunjungan by appointment</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- FORM & SIDEBAR: RAPI + MUDAH DIBACA --}}
            <section id="form" class="mt-8 sm:mt-10 lg:mt-12">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 sm:gap-8 lg:gap-10 items-start">
                    {{-- FORM --}}
                    <div class="lg:col-span-3">
                        <form
                            class="rounded-xl bg-white p-5 sm:p-6 ring-1 ring-gray-200 shadow-sm transition-shadow duration-300 hover:shadow-md"
                            action="#"
                            method="post"
                            onsubmit="event.preventDefault(); contactDemoSubmit(this);"
                            aria-describedby="contact-form-help">
                            {{-- Nama & Email --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                                    <input
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:border-blue-400 focus:ring-blue-400"
                                        required placeholder="Nama lengkap" autocomplete="name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email"
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:border-blue-400 focus:ring-blue-400"
                                        required placeholder="you@example.com" autocomplete="email">
                                </div>
                            </div>

                            {{-- Subjek & Telepon --}}
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subjek</label>
                                    <input
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:border-blue-400 focus:ring-blue-400"
                                        placeholder="Judul pesan">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Telepon (opsional)</label>
                                    <input
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:border-blue-400 focus:ring-blue-400"
                                        placeholder="+62…" inputmode="tel" autocomplete="tel">
                                </div>
                            </div>

                            {{-- Pesan --}}
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Pesan</label>
                                <textarea rows="6"
                                    class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:border-blue-400 focus:ring-blue-400"
                                    placeholder="Tulis pesanmu di sini…"></textarea>
                            </div>

                            {{-- Bantuan --}}
                            <p id="contact-form-help" class="mt-3 text-xs sm:text-sm text-gray-500">
                                Dengan mengirim, Anda setuju pada
                                <a class="text-blue-700 hover:underline" href="{{ route('privacy') }}">Kebijakan Privasi</a>.
                            </p>

                            {{-- Tombol --}}
                            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-xs sm:text-sm text-gray-500">Dukungan cepat: Senin–Jumat 09.00–17.00 WIB</div>
                                <button
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm sm:text-base font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
                                    </svg>
                                    Kirim
                                </button>
                            </div>
                            <p id="contact-demo-toast" class="sr-only mt-3 text-sm"></p>
                        </form>
                    </div>

                    {{-- SIDEBAR: JAM LAYANAN + FAQ MINI --}}
                    <aside class="lg:col-span-2 space-y-6">
                        <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Jam Layanan</h3>
                            <ul class="mt-3 space-y-2 text-sm sm:text-base text-gray-700">
                                <li class="flex items-center justify-between"><span>Senin–Jumat</span><span class="font-medium">09.00–17.00</span></li>
                                <li class="flex items-center justify-between"><span>Sabtu</span><span class="font-medium">10.00–14.00</span></li>
                                <li class="flex items-center justify-between"><span>Minggu/Libur</span><span class="font-medium">Tutup</span></li>
                            </ul>
                        </div>

                        <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm" x-data="{open:0}">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">FAQ Singkat</h3>
                            <div class="mt-3 divide-y divide-gray-200">
                                @foreach ([
                                ['Bagaimana cara berdonasi?', 'Pilih program, klik Donasi, lalu ikuti instruksi pembayaran.'],
                                ['Apakah ada laporan penyaluran?', 'Ya, kami merilis pembaruan dan laporan berkala pada setiap program.'],
                                ['Bisakah menjadi relawan?', 'Bisa. Daftar akun lalu pilih program yang membuka pendaftaran relawan.'],
                                ] as $idx => [$q,$a])
                                <div class="py-3">
                                    <button type="button" class="w-full text-left flex items-center justify-between gap-3"
                                        @click="open === {{ $idx }} ? open = -1 : open = {{ $idx }}">
                                        <span class="text-sm sm:text-base font-medium text-gray-900">{{ $q }}</span>
                                        <span class="rounded-full bg-blue-50 text-blue-600 grid place-items-center h-6 w-6">+</span>
                                    </button>
                                    <div class="mt-2 text-sm text-gray-700" x-show="open === {{ $idx }}" x-collapse>
                                        {{ $a }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </aside>
                </div>
            </section>

            {{-- MAP (RESPONSIF, CLEAN, CTA OVERLAY TERTATA) --}}
            <section id="map" class="mt-8 sm:mt-10 lg:mt-12">
                <div class="relative overflow-hidden rounded-xl ring-1 ring-gray-200">
                    <div class="relative h-64 sm:h-80 md:h-[420px] lg:h-[460px] w-full">
                        <iframe
                            title="Lokasi Yayasan (contoh Monas, Jakarta)"
                            class="absolute inset-0 h-full w-full"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d997.4100305458889!2d106.82715326954548!3d-6.175392099460565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f42f1d2ecf4f%3A0x2b9b88b6b6e0d1c6!2sMonas!5e0!3m2!1sen!2sid!4v1699000000000">
                        </iframe>
                    </div>

                    {{-- Overlay CTA --}}
                    <div class="absolute left-4 bottom-4 sm:left-6 sm:bottom-6">
                        <div class="rounded-lg bg-white/95 backdrop-blur p-4 sm:p-5 ring-1 ring-gray-200 shadow">
                            <div class="text-sm sm:text-base font-semibold text-gray-900">Kantor Yayasan</div>
                            <div class="mt-1 text-xs sm:text-sm text-gray-700">Jl. Contoh Sejahtera No. 10, Jakarta</div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="#" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-3 py-1.5 text-xs sm:text-sm font-semibold text-white hover:bg-blue-700">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                                    </svg>
                                    Buka di Maps
                                </a>
                                <a href="#form" class="inline-flex items-center gap-2 rounded-md ring-1 ring-gray-200 bg-white px-3 py-1.5 text-xs sm:text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
                                    </svg>
                                    Kirim Pesan
                                </a>
                            </div>
                        </div>
                    </div>

                    <noscript>
                        <img src="https://maps.googleapis.com/maps/api/staticmap?center=-6.175392,106.827153&zoom=14&size=1200x600&markers=color:blue|-6.175392,106.827153"
                            alt="Peta lokasi (static)" class="h-64 sm:h-80 md:h-[420px] lg:h-[460px] w-full object-cover" />
                    </noscript>
                </div>
            </section>
        </div>
    </section>

    {{-- REDUCED-MOTION: pakai transisi default Tailwind, tanpa animasi ekstra --}}
    <script>
        // Demo submit sederhana, menampilkan toast mini lalu reset form.
        function contactDemoSubmit(form) {
            const toast = document.getElementById('contact-demo-toast');
            if (toast) {
                toast.classList.remove('sr-only');
                toast.textContent = 'Terkirim (demo). Nanti akan dihubungkan ke backend.';
                toast.className = 'mt-3 rounded-md bg-green-50 px-3 py-2 text-green-700 ring-1 ring-green-200 text-sm';
                setTimeout(() => {
                    toast.className = 'sr-only';
                }, 3500);
            } else {
                alert('Form dummy—nanti dihubungkan ke backend.');
            }
            form.reset();
        }
    </script>
    <x-footer />
</x-app-layout>