{{-- resources/views/components/footer.blade.php --}}
<footer class="relative bg-white">
    {{-- Brand accent: merah → biru (tanpa gradient) --}}
    <div class="h-[2px] w-full" style="background-color:#D21F26"></div>
    <div class="h-[2px] w-full" style="background-color:#145EFC"></div>

    <div class="mx-auto max-w-7xl px-6 pt-10 pb-6">
        {{-- TOP CTA STRIP --}}
        <section
            class="rounded-2xl ring-1 ring-gray-200 bg-white p-5 md:p-6 shadow-sm mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wide uppercase" style="color:#D21F26">
                        Aksi Nyata
                    </p>
                    <h2 class="mt-1 text-xl md:text-2xl font-bold text-gray-900">
                        Yuk, bantu gerakan kebaikan hari ini
                    </h2>
                    <p class="text-gray-600 text-sm md:text-base">
                        Donasi aman, transparan, dan berdampak. Dukung program kami atau ajukan kolaborasi.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('donation') }}"
                        class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95 transition"
                        style="background-color:#D21F26">
                        Donasi Sekarang
                    </a>
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold ring-1 ring-gray-200 text-gray-800 hover:bg-gray-50 transition">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </section>

        {{-- MAIN GRID --}}
        <section class="grid gap-8 md:grid-cols-12">
            {{-- BRAND + ALAMAT (md: 5 kolom) --}}
            <div class="md:col-span-5">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('favicon.ico') }}" alt="Logo YayasanKita"
                        class="h-9 w-9 rounded-lg ring-1 ring-gray-200">
                    <div>
                        <div class="text-lg font-extrabold tracking-tight text-gray-900">
                            Yayasan<span style="color:#145EFC">Kita</span>
                        </div>
                        <div class="mt-0.5 inline-flex items-center gap-2 text-xs font-medium">
                            <span class="inline-block h-1.5 w-6 rounded-full" style="background-color:#D21F26"></span>
                            <span class="inline-block h-1.5 w-6 rounded-full" style="background-color:#145EFC"></span>
                        </div>
                    </div>
                </div>

                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Platform kolaborasi kebaikan—pendidikan, kemanusiaan, dan pemberdayaan ekonomi.
                    Transparansi & akuntabilitas jadi prioritas kami.
                </p>

                <ul class="mt-4 space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        {{-- Map Pin --}}
                        <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#145EFC">
                            <path d="M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" />
                        </svg>
                        <span>Jl. Contoh Sejahtera No. 10, Jakarta</span>
                    </li>
                    <li class="flex items-start gap-2">
                        {{-- Phone --}}
                        <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#D21F26">
                            <path d="M6.62 10.79a15.1 15.1 0 006.59 6.59l1.82-1.82a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1C10.2 21 3 13.8 3 5a1 1 0 011-1h2.11a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1 1 0 01-.24 1.02l-1.82 1.82z" />
                        </svg>
                        <span>+62 21 1234 5678 (Sen–Jum 09.00–17.00)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        {{-- Mail --}}
                        <svg class="h-5 w-5 flex-none" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#145EFC">
                            <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v1l-9 6L3 6V5z" />
                            <path d="M3 8l9 6 9-6v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                        </svg>
                        <span>halo@yayasan.test</span>
                    </li>
                </ul>

                {{-- Sosial --}}
                <div class="mt-5">
                    <p class="text-sm font-semibold text-gray-900">Ikuti Kami</p>
                    <div class="mt-3 flex gap-2">
                        <a href="#" class="h-9 w-9 grid place-items-center rounded-full ring-1 ring-gray-200 bg-white text-gray-700 hover:bg-gray-50" aria-label="X / Twitter">
                            {{-- X/Twitter --}}
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#145EFC">
                                <path d="M18.9 3H21l-6.5 7.4L22 21h-6.9l-4.3-5.4L5.8 21H3l7-8-6.6-8h7l4 5 4.5-5z" />
                            </svg>
                        </a>
                        <a href="#" class="h-9 w-9 grid place-items-center rounded-full ring-1 ring-gray-200 bg-white text-gray-700 hover:bg-gray-50" aria-label="Facebook">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#145EFC">
                                <path d="M13 22v-7h2.5l.5-3H13V9.5c0-.9.3-1.5 1.7-1.5H16V5.2c-.3 0-1.2-.2-2.3-.2-2.2 0-3.7 1.3-3.7 3.8V12H7v3h3v7h3z" />
                            </svg>
                        </a>
                        <a href="#" class="h-9 w-9 grid place-items-center rounded-full ring-1 ring-gray-200 bg-white text-gray-700 hover:bg-gray-50" aria-label="Instagram">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#D21F26">
                                <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A5.5 5.5 0 1112 18.5 5.5 5.5 0 0112 7.5zm0 2A3.5 3.5 0 1015.5 13 3.5 3.5 0 0012 9.5zM18 6.8a1 1 0 110 2 1 1 0 010-2z" />
                            </svg>
                        </a>
                        <a href="#" class="h-9 w-9 grid place-items-center rounded-full ring-1 ring-gray-200 bg-white text-gray-700 hover:bg-gray-50" aria-label="YouTube">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:#D21F26">
                                <path d="M23 12s0-3.4-.4-5a3 3 0 00-2-2C18.9 4.4 12 4.4 12 4.4s-6.9 0-8.6.6a3 3 0 00-2 2C1 8.6 1 12 1 12s0 3.4.4 5a3 3 0 002 2c1.7.6 8.6.6 8.6.6s6.9 0 8.6-.6a3 3 0 002-2c.4-1.6.4-5 .4-5zM10 15.5v-7l6 3.5-6 3.5z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TAUTAN CEPAT (md: 3 kolom) --}}
            <div class="md:col-span-3">
                <h3 class="text-sm font-semibold text-gray-900">Tautan</h3>
                <ul class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-700">
                    <li><a href="{{ route('home') }}" class="hover:text-gray-900">Beranda</a></li>
                    <li><a href="{{ route('donation') }}" class="hover:text-gray-900">Donasi</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-gray-900">Tentang</a></li>
                    <li><a href="{{ route('team') }}" class="hover:text-gray-900">Tim</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-gray-900">Kontak</a></li>
                    <li><a href="#" class="hover:text-gray-900">Laporan</a></li>
                </ul>
                <div class="mt-5">
                    <h4 class="text-sm font-semibold text-gray-900">Bantuan</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-700">
                        <li><a href="{{ route('faq') }}" class="hover:text-gray-900">FAQ</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-gray-900">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-gray-900">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            {{-- NEWSLETTER (md: 4 kolom) --}}
            <div class="md:col-span-4">
                <h3 class="text-sm font-semibold text-gray-900">Newsletter</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Dapatkan update program dan laporan distribusi. 1–2 email per bulan, tanpa spam.
                </p>
                <form method="POST" action="#"
                    class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-2">
                    @csrf
                    <label for="nl-email" class="sr-only">Email</label>
                    <input id="nl-email" type="email" name="email" required
                        placeholder="you@example.com"
                        class="col-span-2 rounded-xl border-gray-200 px-3 py-2.5 text-sm focus:border-[#145EFC] focus:ring-[#145EFC]">
                    <button type="submit"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95 transition"
                        style="background-color:#145EFC">
                        Berlangganan
                    </button>
                    <p class="col-span-full text-xs text-gray-500">
                        Dengan berlangganan, Anda menyetujui kebijakan privasi kami.
                    </p>
                </form>

                {{-- Mini badges merah → biru --}}
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-block h-1.5 w-10 rounded-full" style="background-color:#D21F26"></span>
                    <span class="inline-block h-1.5 w-10 rounded-full" style="background-color:#145EFC"></span>
                </div>
            </div>
        </section>

        {{-- BOTTOM BAR --}}
        <section class="mt-10">
            <div class="h-[1px] w-full bg-gray-100"></div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between py-4">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} YayasanKita. All rights reserved.
                </p>
                <div class="flex items-center gap-4 text-xs">
                    <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-gray-900">Privasi</a>
                    <span class="h-3 w-px bg-gray-200"></span>
                    <a href="#" class="text-gray-600 hover:text-gray-900">Ketentuan</a>
                    <span class="h-3 w-px bg-gray-200"></span>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900">Kontak</a>
                </div>
            </div>
        </section>
    </div>
</footer>