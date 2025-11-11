{{-- resources/views/front/terms.blade.php --}}
<x-app-layout>
    {{-- Print-friendly & helper styles --}}
    <style>
        /* Hilangkan marker default <summary> agar tidak geser layout */
        details>summary::-webkit-details-marker {
            display: none;
        }

        details>summary {
            list-style: none;
        }

        /* Saat print: fokus ke konten utama saja */
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
            .print-container p {
                color: #000 !important;
            }
        }
    </style>

    <section class="bg-white">
        {{-- Brand bars: Merah → Biru (tanpa gradient) --}}
        <div class="h-[2px] w-full" style="background-color:#D21F26"></div>
        <div class="h-[2px] w-full" style="background-color:#145EFC"></div>

        <div class="mx-auto max-w-7xl px-6 py-10">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <span class="mx-1.5">/</span>
                <span class="text-gray-700 font-medium">Syarat & Ketentuan</span>
            </nav>

            {{-- Hero (solid merah, tanpa gradient) --}}
            <header
                class="mt-4 rounded-3xl overflow-hidden ring-1 text-white"
                style="background-color:#D21F26; box-shadow:0 10px 30px rgba(210,31,38,.15); border-color:rgba(210,31,38,.25)">
                <div class="relative px-8 py-10 md:px-12 md:py-12">
                    <div class="absolute -right-16 -top-16 h-56 w-56 bg-white/10 rounded-full blur-2xl"></div>

                    <h1 class="text-3xl md:text-4xl font-bold">Syarat & Ketentuan</h1>
                    <p class="mt-2 text-white/90 max-w-2xl">
                        Ketentuan penggunaan layanan situs yayasan untuk donasi, transparansi, dan kolaborasi.
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
                            Terakhir diperbarui:
                            <time class="font-medium">{{ now()->format('d M Y') }}</time>
                        </span>

                        {{-- Print button: putih → teks biru (sekunder) --}}
                        <button onclick="window.print()" type="button"
                            class="ml-auto inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold hover:bg-gray-50 print-hide"
                            style="color:#145EFC"
                            aria-label="Cetak dokumen">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6 9V2h12v7H6zm0 13v-6h12v6H6zM4 10h16a2 2 0 012 2v4h-4v-4H6v4H2v-4a2 2 0 012-2z" />
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </header>

            {{-- Main grid: TOC + Content --}}
            <div class="mt-10 grid lg:grid-cols-12 gap-8">

                {{-- TOC (sticky on lg) --}}
                <aside class="lg:col-span-4">
                    <div class="lg:sticky lg:top-6 space-y-4">

                        {{-- Mobile dropdown --}}
                        <details class="group lg:hidden rounded-2xl ring-1 ring-gray-200 bg-white">
                            <summary class="flex items-center justify-between px-4 py-3 cursor-pointer [&::-webkit-details-marker]:hidden">
                                <span class="font-semibold text-gray-900">Daftar Isi</span>
                                <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 14l-6-6h12l-6 6z" />
                                </svg>
                            </summary>
                            <nav class="border-t border-gray-100 p-3">
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#penggunaan-layanan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">1. Penggunaan Layanan</a></li>
                                    <li><a href="#donasi" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">2. Donasi</a></li>
                                    <li><a href="#konten" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">3. Konten</a></li>
                                    <li><a href="#perubahan" class="toc-link block rounded-lg px-3 py-2 hover:bg-gray-50">4. Perubahan</a></li>
                                </ul>
                            </nav>
                        </details>

                        {{-- Desktop card --}}
                        <div class="hidden lg:block rounded-2xl ring-1 ring-gray-200 bg-white p-5">
                            <div class="text-sm font-semibold text-gray-900">Daftar Isi</div>
                            <nav class="mt-3">
                                <ul class="space-y-1 text-sm">
                                    <li>
                                        <a href="#penggunaan-layanan" class="toc-link flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-gray-50">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-300 toc-bullet"></span>
                                            1. Penggunaan Layanan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#donasi" class="toc-link flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-gray-50">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-300 toc-bullet"></span>
                                            2. Donasi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#konten" class="toc-link flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-gray-50">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-300 toc-bullet"></span>
                                            3. Konten
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#perubahan" class="toc-link flex items-center gap-2 rounded-lg px-3 py-2 hover:bg-gray-50">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-300 toc-bullet"></span>
                                            4. Perubahan
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Help card (solid biru sekunder, tanpa gradient) --}}
                        <div class="help-card rounded-2xl p-5 text-white" style="background-color:#145EFC">
                            <div class="font-semibold">Butuh bantuan lanjutan?</div>
                            <p class="text-white/90 text-sm">Jika ada yang kurang jelas, hubungi tim kami.</p>
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
                            <p class="text-sm text-gray-500">Harap baca dengan seksama sebelum menggunakan layanan kami.</p>

                            {{-- 1) Penggunaan Layanan --}}
                            <h2 id="penggunaan-layanan" class="scroll-mt-24 group">
                                1. Penggunaan Layanan
                                <button type="button"
                                    class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan"
                                    onclick="copyLink('#penggunaan-layanan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC" aria-hidden="true">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Anda setuju menggunakan layanan sesuai hukum yang berlaku dan tidak untuk tindakan melanggar hukum.</p>

                            {{-- 2) Donasi --}}
                            <h2 id="donasi" class="scroll-mt-24 group">
                                2. Donasi
                                <button type="button"
                                    class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan"
                                    onclick="copyLink('#donasi')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC" aria-hidden="true">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 000-10zM16 5h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Donasi bersifat sukarela. Pengembalian dana hanya berlaku pada kondisi tertentu sesuai kebijakan yayasan.</p>

                            {{-- 3) Konten --}}
                            <h2 id="konten" class="scroll-mt-24 group">
                                3. Konten
                                <button type="button"
                                    class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan"
                                    onclick="copyLink('#konten')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC" aria-hidden="true">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Konten kampanye dan laporan disediakan untuk transparansi. Dilarang mendistribusikan ulang tanpa izin.</p>

                            {{-- 4) Perubahan --}}
                            <h2 id="perubahan" class="scroll-mt-24 group">
                                4. Perubahan
                                <button type="button"
                                    class="ml-2 align-middle opacity-0 group-hover:opacity-100 transition"
                                    aria-label="Salin tautan"
                                    onclick="copyLink('#perubahan')">
                                    <svg class="h-4 w-4 inline-block" viewBox="0 0 24 24" fill="#145EFC" aria-hidden="true">
                                        <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                    </svg>
                                </button>
                            </h2>
                            <p>Kami berhak mengubah syarat ini sewaktu-waktu. Versi terbaru akan dipublikasikan di halaman ini.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Back to top floating (biru sekunder) --}}
    <button id="backToTop"
        class="fixed bottom-6 right-6 z-40 hidden h-11 w-11 rounded-full text-white shadow-lg ring-1 hover:opacity-95"
        style="background-color:#145EFC; box-shadow:0 10px 20px rgba(20,94,252,.25)"
        aria-label="Kembali ke atas"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
        ↑
    </button>

    {{-- Scripts: TOC active, copy link, back to top, smooth hash --}}
    <script>
        // Copy section link
        function copyLink(hash) {
            const url = `${location.origin}${location.pathname}${hash}`;
            navigator.clipboard?.writeText(url);
        }

        (function() {
            const sections = ['#penggunaan-layanan', '#donasi', '#konten', '#perubahan']
                .map(id => document.querySelector(id))
                .filter(Boolean);

            const tocLinks = Array.from(document.querySelectorAll('.toc-link'));
            const backToTop = document.getElementById('backToTop');

            function setActive(id) {
                tocLinks.forEach(a => {
                    const active = a.getAttribute('href') === id;
                    a.classList.toggle('bg-blue-50', active);
                    a.classList.toggle('text-blue-700', active);
                    const bullet = a.querySelector('.toc-bullet');
                    if (bullet) bullet.classList.toggle('bg-blue-600', active);
                });
            }

            function onScroll() {
                let current = null;
                const offset = 120; // agar tidak tertutup header
                for (const s of sections) {
                    const top = s.getBoundingClientRect().top;
                    if (top - offset <= 0) current = s.id;
                }
                setActive(current ? `#${current}` : '#penggunaan-layanan');

                // show/hide backToTop
                backToTop.classList.toggle('hidden', window.scrollY < 300);
            }

            document.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();

            // Smooth scroll untuk TOC links
            tocLinks.forEach(a => {
                a.addEventListener('click', (e) => {
                    const href = a.getAttribute('href');
                    if (!href || !href.startsWith('#')) return;
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

            // Jika halaman dibuka dengan hash, scroll halus ke sana
            if (location.hash) {
                const target = document.querySelector(location.hash);
                target?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        })();
    </script>

    <x-footer />
</x-app-layout>