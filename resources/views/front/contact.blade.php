{{-- resources/views/front/contact.blade.php --}}
<x-app-layout>
    <section class="relative overflow-hidden bg-white">
        {{-- BACKDROP netral (tanpa gradien warna utama) --}}
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-28 -right-28 h-[28rem] w-[28rem] rounded-full bg-black/5 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-[24rem] w-[24rem] rounded-full bg-black/5 blur-3xl"></div>
        </div>

        {{-- Top accent: MERAH lalu BIRU --}}
        <div class="w-full">
            <div class="h-[2px] w-full" style="background: var(--brand-red)"></div>
            <div class="h-[2px] w-full" style="background: var(--brand-blue)"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 sm:py-12 lg:py-16">
            {{-- HEADER --}}
            <header class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10 items-start">
                <div class="lg:col-span-2">
                    <p class="text-xs sm:text-sm font-semibold tracking-wide uppercase" style="color: var(--brand-red);">
                        Hubungi Kami
                    </p>

                    <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight text-gray-900">
                        Mari kolaborasi untuk <span style="color: var(--brand-blue)">kebaikan</span>
                    </h1>

                    <p class="mt-3 sm:mt-4 text-gray-600 text-base sm:text-lg leading-relaxed max-w-3xl">
                        Ada pertanyaan, saran program, atau kemitraan? Tim kami siap membantu. Rata-rata balasan 1–3 jam kerja.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        {{-- Tombol utama: MERAH --}}
                        <a href="#form"
                            class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm sm:text-base font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition"
                            style="background: var(--brand-red); --ring-color: var(--brand-red);"
                            onmouseover="this.style.filter='brightness(0.95)';"
                            onmouseout="this.style.filter=''">
                            <x-heroicon-o-paper-airplane class="h-5 w-5" />
                            Kirim Pesan
                        </a>

                        {{-- Tombol sekunder: outline BIRU --}}
                        <a href="#map"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm sm:text-base focus:outline-none focus:ring-2 transition"
                            style="color: var(--brand-blue); border: 1px solid color-mix(in srgb, var(--brand-blue) 13%, transparent); --ring-color: var(--brand-blue);">
                            <x-heroicon-o-map-pin class="h-5 w-5" />
                            Lihat Lokasi
                        </a>
                    </div>
                </div>

                {{-- ILLUSTRATION --}}
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
                                <span class="h-2.5 w-2.5 rounded-full" style="background: var(--brand-blue)"></span>
                                Online pada jam kerja
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- 3 KARTU INFORMASI --}}
            <section class="mt-8 sm:mt-10 lg:mt-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    {{-- Email (MERAH) --}}
                    <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="grid h-11 w-11 place-items-center rounded-xl"
                                style="background: color-mix(in srgb, var(--brand-red) 12%, white); color: var(--brand-red);">
                                <x-heroicon-o-envelope class="h-5 w-5" />
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">Email</div>
                                <div class="text-sm sm:text-base text-gray-600">halo@yayasan.test</div>
                                <div class="mt-2 text-xs text-gray-500">Balasan ± 1–3 jam kerja</div>
                            </div>
                        </div>
                    </div>

                    {{-- Telepon (BIRU) --}}
                    <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="grid h-11 w-11 place-items-center rounded-xl"
                                style="background: color-mix(in srgb, var(--brand-blue) 12%, white); color: var(--brand-blue);">
                                <x-heroicon-o-phone class="h-5 w-5" />
                            </div>
                            <div>
                                <div class="text-sm sm:text-base font-semibold text-gray-900">Telepon (jam kerja)</div>
                                <div class="text-sm sm:text-base text-gray-600">+62 21 1234 5678</div>
                                <div class="mt-2 text-xs text-gray-500">Senin–Jumat 09.00–17.00 WIB</div>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat (MERAH) --}}
                    <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="grid h-11 w-11 place-items-center rounded-xl"
                                style="background: color-mix(in srgb, var(--brand-red) 12%, white); color: var(--brand-red);">
                                <x-heroicon-o-magnifying-glass class="h-5 w-5" />
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

            {{-- FORM & SIDEBAR --}}
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
                            @csrf

                            {{-- Nama & Email --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                                    <input name="name"
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:ring-2"
                                        style="--tw-ring-color: var(--brand-blue); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000;"
                                        required placeholder="Nama lengkap" autocomplete="name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email"
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:ring-2"
                                        style="--tw-ring-color: var(--brand-blue);"
                                        required placeholder="you@example.com" autocomplete="email">
                                </div>
                            </div>

                            {{-- Subjek & Telepon --}}
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subjek</label>
                                    <input name="subject"
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:ring-2"
                                        style="--tw-ring-color: var(--brand-blue);"
                                        placeholder="Judul pesan">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Telepon (opsional)</label>
                                    <input name="phone"
                                        class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:ring-2"
                                        style="--tw-ring-color: var(--brand-blue);"
                                        placeholder="+62…" inputmode="tel" autocomplete="tel">
                                </div>
                            </div>

                            {{-- Pesan --}}
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Pesan</label>
                                <textarea name="message" rows="6"
                                    class="mt-1 block w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm sm:text-base focus:ring-2"
                                    style="--tw-ring-color: var(--brand-blue);"
                                    placeholder="Tulis pesanmu di sini…"></textarea>
                            </div>

                            {{-- Bantuan --}}
                            <p id="contact-form-help" class="mt-3 text-xs sm:text-sm text-gray-500">
                                Dengan mengirim, Anda setuju pada
                                <a class="hover:underline" style="color: var(--brand-blue)" href="{{ url('/privacy') }}">
                                    Kebijakan Privasi
                                </a>.
                            </p>

                            {{-- Tombol --}}
                            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-xs sm:text-sm text-gray-500">Dukungan cepat: Senin–Jumat 09.00–17.00 WIB</div>

                                {{-- Kirim (MERAH) --}}
                                <button
                                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm sm:text-base font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    style="background: var(--brand-red); --ring-color: var(--brand-red);"
                                    onmouseover="this.style.filter='brightness(0.95)';"
                                    onmouseout="this.style.filter=''">
                                    <x-heroicon-o-paper-airplane class="h-5 w-5" />
                                    Kirim
                                </button>
                            </div>
                            <p id="contact-demo-toast" class="sr-only mt-3 text-sm"></p>
                        </form>
                    </div>

                    {{-- SIDEBAR --}}
                    <aside class="lg:col-span-2 space-y-6">
                        <div class="rounded-xl bg-white ring-1 ring-gray-200 p-5 sm:p-6 shadow-sm">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Jam Layanan</h3>
                            <ul class="mt-3 space-y-2 text-sm sm:text-base text-gray-700">
                                <li class="flex items-center justify-between">
                                    <span>Senin–Jumat</span>
                                    <span class="font-semibold" style="color: var(--brand-blue)">09.00–17.00</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span>Sabtu</span>
                                    <span class="font-semibold" style="color: var(--brand-blue)">10.00–14.00</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span>Minggu/Libur</span>
                                    <span class="font-semibold" style="color: var(--brand-red)">Tutup</span>
                                </li>
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
                                        <span class="rounded-full grid place-items-center h-6 w-6 text-white"
                                            style="background: var(--brand-blue)">+</span>
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

            {{-- MAP --}}
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
                                {{-- Buka Maps (BIRU) --}}
                                <a href="#"
                                    class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs sm:text-sm font-semibold text-white"
                                    style="background: var(--brand-blue);"
                                    onmouseover="this.style.filter='brightness(0.95)';"
                                    onmouseout="this.style.filter=''">
                                    <x-heroicon-o-map class="h-4 w-4" />
                                    Buka di Maps
                                </a>
                                {{-- Kirim Pesan (MERAH outline) --}}
                                <a href="#form"
                                    class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-1.5 text-xs sm:text-sm"
                                    style="color: var(--brand-red); border:1px solid color-mix(in srgb, var(--brand-red) 20%, transparent);">
                                    <x-heroicon-o-paper-airplane class="h-4 w-4" />
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

    {{-- Reduced-motion & demo submit --}}
    <script>
        function contactDemoSubmit(form) {
            const toast = document.getElementById('contact-demo-toast');
            if (toast) {
                toast.classList.remove('sr-only');
                toast.textContent = 'Terkirim (demo). Nanti akan dihubungkan ke backend.';
                toast.className = 'mt-3 rounded-md px-3 py-2 text-sm';
                toast.style.background = '#ECFDF5';
                toast.style.color = '#166534';
                toast.style.border = '1px solid #BBF7D0';
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