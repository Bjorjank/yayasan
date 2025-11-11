{{-- resources/views/front/privacy.blade.php --}}
<x-app-layout>
    {{-- Print-friendly & helpers --}}
    <style>
        details>summary::-webkit-details-marker {
            display: none;
        }

        details>summary {
            list-style: none;
        }

        @media print {

            nav[aria-label="Breadcrumb"],
            aside,
            #backToTop,
            .print-hide,
            .help-card {
                display: none !important;
            }

            .print-container {
                max-width: 800px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .print-container h1,
            .print-container h2,
            .print-container p,
            .print-container li {
                color: #000 !important;
            }
        }
    </style>

    <section class="bg-white">
        {{-- Brand bars: mulai Merah → Biru (tanpa gradient) --}}
        <div class="h-[2px] w-full" style="background-color:#D21F26"></div>
        <div class="h-[2px] w-full" style="background-color:#145EFC"></div>

        <div class="mx-auto max-w-7xl px-6 py-10">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <span class="mx-1.5">/</span>
                <span class="text-gray-700 font-medium">Kebijakan Privasi</span>
            </nav>

            {{-- Hero (solid merah) --}}
            <header
                class="mt-4 rounded-3xl overflow-hidden ring-1 text-white"
                style="background-color:#D21F26; box-shadow:0 10px 30px rgba(210,31,38,.15); border-color:rgba(210,31,38,.25)">
                <div class="relative px-8 py-10 md:px-12 md:py-12">
                    <div class="absolute -right-16 -top-16 h-56 w-56 bg-white/10 rounded-full blur-2xl"></div>

                    <h1 class="text-3xl md:text-4xl font-bold">Kebijakan Privasi</h1>
                    <p class="mt-2 text-white/90 max-w-2xl">
                        Penjelasan bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 ring-1"
                            style="background-color:rgba(255,255,255,.10); border-color:rgba(255,255,255,.25)">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6 2h12v2H6V2zm6 4a6 6 0 100 12 6 6 0 000-12zM5 20h14v2H5v-2z" />
                            </svg>
                            Versi Dokumen <strong class="ml-1 font-semibold">1.0</strong>
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 ring-1"
                            style="background-color:rgba(255,255,255,.10); border-color:rgba(255,255,255,.25)">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M19 3H5a2 2 0 00-2 2v14l4-4h12a2 2 0 002-2V5a2 2 0 00-2-2z" />
                            </svg>
                            Terakhir diperbarui: <time class="font-medium">{{ now()->format('d M Y') }}</time>
                        </span>

                        {{-- Print button: putih → teks biru (sekunder) --}}
                        <button onclick="window.print()" type="button"
                            class="ml-auto inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold hover:bg-gray-50 print-hide"
                            style="color:#145EFC"
                            aria-label="Cetak dokumen">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 9V2h12v7H6zm0 13v-6h12v6H6zM4 10h16a2 2 0 012 2v4h-4v-4H6v4H2v-4a2 2 0 012-2z" />
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </header>

            {{-- Main grid --}}
            <div class="mt-10 grid lg:grid-cols-12 gap-8">

                {{-- TOC --}}
                <aside class="lg:col-span-4">
                    <div class="lg:sticky lg:top-6 space-y-4">

                        {{-- Mobile dropdown TOC --}}
                        <details class="group lg:hidden rounded-2xl ring-1 ring-gray-200 bg-white">
                            <summary class="flex items-center justify-between px-4 py-3 cursor-pointer [&::-webkit-details-marker]:hidden">
                                <span class="font-semibold text-gray-900">Daftar Isi</span>
                                <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 14l-6-6h12l-6 6z" />
                                </svg>
                            </summary>
                            <nav class="border-t border-gray-100 p-3">
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#pendahuluan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">1. Pendahuluan</a></li>
                                    <li><a href="#data-dikumpulkan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">2. Data yang Kami Kumpulkan</a></li>
                                    <li><a href="#tujuan-pengolahan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">3. Tujuan Pengolahan</a></li>
                                    <li><a href="#berbagi-data" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">4. Berbagi Data</a></li>
                                    <li><a href="#retensi-keamanan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">5. Retensi & Keamanan</a></li>
                                    <li><a href="#cookies" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">6. Cookies & Tracking</a></li>
                                    <li><a href="#hak-anda" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">7. Hak Anda</a></li>
                                    <li><a href="#kontak" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">8. Kontak</a></li>
                                    <li><a href="#perubahan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">9. Perubahan</a></li>
                                </ul>
                            </nav>
                        </details>

                        {{-- Desktop card TOC --}}
                        <div class="hidden lg:block rounded-2xl ring-1 ring-gray-200 bg-white p-5">
                            <div class="text-sm font-semibold text-gray-900">Daftar Isi</div>
                            <nav class="mt-3">
                                <ul class="space-y-1 text-sm">
                                    @foreach ([
                                    ['#pendahuluan','1. Pendahuluan'],
                                    ['#data-dikumpulkan','2. Data yang Kami Kumpulkan'],
                                    ['#tujuan-pengolahan','3. Tujuan Pengolahan'],
                                    ['#berbagi-data','4. Berbagi Data'],
                                    ['#retensi-keamanan','5. Retensi & Keamanan'],
                                    ['#cookies','6. Cookies & Tracking'],
                                    ['#hak-anda','7. Hak Anda'],
                                    ['#kontak','8. Kontak'],
                                    ['#perubahan','9. Perubahan'],
                                    ] as [$href,$label])
                                    <li>
                                        <a href="{{ $href }}" class="toc-link flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-gray-50">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-300 toc-bullet"></span>
                                            {{ $label }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>

                        {{-- Help card (solid biru sekunder) --}}
                        <div class="help-card rounded-2xl p-5 text-white"
                            style="background-color:#145EFC">
                            <div class="font-semibold">Butuh bantuan data?</div>
                            <p class="text-white/90 text-sm">Minta salinan atau penghapusan data pribadi Anda.</p>
                            <a href="{{ route('contact') }}"
                                class="mt-3 inline-block rounded-xl bg-white px-4 py-2 font-semibold hover:bg-gray-50"
                                style="color:#145EFC">
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                </aside>

                {{-- Content --}}
                <article class="lg:col-span-8">
                    <div class="print-container rounded-3xl ring-1 ring-gray-200 bg-white p-6 md:p-8">
                        <div class="prose prose-blue max-w-none">
                            <p class="text-sm text-gray-500">Dokumen ini menjelaskan praktik privasi kami secara ringkas dan mudah dipahami.</p>

                            {{-- 1) Pendahuluan --}}
                            <h2 id="pendahuluan" class="scroll-mt-24 group">
                                1. Pendahuluan
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#pendahuluan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Kami berkomitmen menjaga privasi dan keamanan data Anda saat menggunakan layanan yayasan.</p>

                            {{-- 2) Data yang Kami Kumpulkan --}}
                            <h2 id="data-dikumpulkan" class="scroll-mt-24 group">
                                2. Data yang Kami Kumpulkan
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#data-dikumpulkan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <ul>
                                <li><strong>Data akun:</strong> nama, email.</li>
                                <li><strong>Data transaksi donasi:</strong> nominal, metode, referensi.</li>
                                <li><strong>Data teknis:</strong> alamat IP, user-agent, log akses.</li>
                            </ul>

                            {{-- 3) Tujuan Pengolahan --}}
                            <h2 id="tujuan-pengolahan" class="scroll-mt-24 group">
                                3. Tujuan Pengolahan
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#tujuan-pengolahan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <ul>
                                <li>Memproses donasi dan menerbitkan laporan penyaluran.</li>
                                <li>Komunikasi terkait program, pembaruan, dan dukungan.</li>
                                <li>Keamanan layanan serta pencegahan kecurangan.</li>
                            </ul>

                            {{-- 4) Berbagi Data --}}
                            <h2 id="berbagi-data" class="scroll-mt-24 group">
                                4. Berbagi Data
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#berbagi-data')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 0 1 5-5h3v2H8a3 3 0 1 0 0 6h3v2H8a5 5 0 0 1-5-5zM16 5h-3v2h3a3 3 0 0 1 0 6h-3v2h3a5 5 0 0 0 0-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Kami dapat membagikan data kepada penyedia pembayaran dan mitra operasional sesuai kebutuhan transaksi, peraturan yang berlaku, serta perjanjian pemrosesan data.</p>

                            {{-- 5) Retensi & Keamanan --}}
                            <h2 id="retensi-keamanan" class="scroll-mt-24 group">
                                5. Retensi & Keamanan
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#retensi-keamanan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <ul>
                                <li><strong>Retensi:</strong> Data disimpan selama diperlukan untuk tujuan di atas atau selama diwajibkan hukum.</li>
                                <li><strong>Keamanan:</strong> TLS/HTTPS, kontrol akses berbasis peran, penyimpanan terenkripsi untuk data sensitif.</li>
                            </ul>

                            {{-- 6) Cookies & Tracking --}}
                            <h2 id="cookies" class="scroll-mt-24 group">
                                6. Cookies & Tracking
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#cookies')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Kami menggunakan cookies esensial untuk fungsionalitas dasar dan, jika diaktifkan, cookies analitik untuk memahami penggunaan layanan.</p>

                            {{-- 7) Hak Anda --}}
                            <h2 id="hak-anda" class="scroll-mt-24 group">
                                7. Hak Anda
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#hak-anda')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <ul>
                                <li>Meminta akses ke data pribadi Anda.</li>
                                <li>Memperbaiki atau memperbarui data yang tidak akurat.</li>
                                <li>Meminta penghapusan data sesuai ketentuan.</li>
                            </ul>
                            <p>Untuk menggunakan hak tersebut, silakan <a href="{{ route('contact') }}" style="color:#145EFC">hubungi kami</a>.</p>

                            {{-- 8) Kontak --}}
                            <h2 id="kontak" class="scroll-mt-24 group">
                                8. Kontak
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#kontak')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Email: <a href="mailto:halo@yayasan.test" style="color:#145EFC">halo@yayasan.test</a></p>
                            <p>Alamat: Jl. Contoh Sejahtera No. 10, Jakarta</p>

                            {{-- 9) Perubahan --}}
                            <h2 id="perubahan" class="scroll-mt-24 group">
                                9. Perubahan
                                <button type="button" class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan" onclick="copyLink('#perubahan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Kami dapat memperbarui kebijakan ini sewaktu-waktu. Versi terbaru akan dipublikasikan pada halaman ini.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Back to top (sekunder biru) --}}
    <button id="backToTop"
        class="fixed bottom-6 right-6 z-40 hidden h-11 w-11 rounded-full text-white shadow-lg ring-1 hover:opacity-95"
        style="background-color:#145EFC; box-shadow:0 10px 20px rgba(20,94,252,.25)"
        aria-label="Kembali ke atas"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

    {{-- Scripts: toc active, smooth scroll, copy link, back-to-top --}}
    <script>
        function copyLink(hash) {
            const url = `${location.origin}${location.pathname}${hash}`;
            navigator.clipboard?.writeText(url);
        }

        (function() {
            const ids = ['#pendahuluan', '#data-dikumpulkan', '#tujuan-pengolahan', '#berbagi-data', '#retensi-keamanan', '#cookies', '#hak-anda', '#kontak', '#perubahan'];
            const sections = ids.map(id => document.querySelector(id)).filter(Boolean);
            const tocLinks = Array.from(document.querySelectorAll('.toc-link'));
            const backToTop = document.getElementById('backToTop');

            function setActive(id) {
                tocLinks.forEach(a => {
                    const active = a.getAttribute('href') === id;
                    a.classList.toggle('bg-blue-50', active);
                    a.classList.toggle('text-blue-700', active);
                    const b = a.querySelector('.toc-bullet');
                    if (b) b.classList.toggle('bg-blue-600', active);
                });
            }

            function onScroll() {
                let current = null;
                const offset = 120;
                for (const s of sections) {
                    const top = s.getBoundingClientRect().top;
                    if (top - offset <= 0) current = s.id;
                }
                setActive(current ? `#${current}` : '#pendahuluan');
                backToTop.classList.toggle('hidden', window.scrollY < 300);
            }

            document.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();

            // Smooth scroll TOC
            tocLinks.forEach(a => {
                a.addEventListener('click', (e) => {
                    const href = a.getAttribute('href');
                    if (!href?.startsWith('#')) return;
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        window.history.replaceState(null, '', href);
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Open with hash
            if (location.hash) {
                const t = document.querySelector(location.hash);
                t?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        })();
    </script>

    <x-footer />
</x-app-layout>