{{-- resources/views/front/faq.blade.php --}}
<x-app-layout>
    <style>
        /* Hilangkan marker default <summary> */
        details>summary::-webkit-details-marker {
            display: none;
        }

        details>summary {
            list-style: none;
        }

        /* Fokus ring util untuk aksesibilitas */
        .focus-ring:focus {
            outline: 2px solid var(--brand-blue, #145EFC);
            outline-offset: 2px;
        }
    </style>

    <section class="bg-white">
        {{-- Brand accent: merah lalu biru (tanpa gradient) --}}
        <div class="h-[2px] w-full" style="background-color: var(--brand-red, #D21F26)"></div>
        <div class="h-[2px] w-full" style="background-color: var(--brand-blue, #145EFC)"></div>

        <div class="mx-auto max-w-7xl px-6 py-12">
            {{-- HEADER --}}
            <header class="max-w-3xl">
                <p class="text-[11px] sm:text-xs font-semibold tracking-wide uppercase"
                    style="color: var(--brand-red, #D21F26)">FAQ</p>
                <h1 class="mt-2 text-3xl md:text-4xl font-bold leading-tight text-gray-900">
                    Pertanyaan yang Sering Diajukan
                </h1>
                <p class="mt-3 text-gray-600">
                    Jawaban singkat tentang donasi, relawan, akun, dan keamanan. Jika belum ketemu, hubungi kami.
                </p>
                {{-- garis aksen kecil merah → biru --}}
                <div class="mt-4 flex gap-1">
                    <span class="inline-block h-1 w-16 rounded-full" style="background-color: var(--brand-red, #D21F26)"></span>
                    <span class="inline-block h-1 w-16 rounded-full" style="background-color: var(--brand-blue, #145EFC)"></span>
                </div>
            </header>

            {{-- BAR UTILITAS: SEARCH (kiri) + TOMBOL (kanan) --}}
            <div class="mt-6">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    {{-- SEARCH WRAPPER --}}
                    <div class="sm:col-span-8">
                        <div class="relative h-11">
                            <input
                                id="faqSearch"
                                type="search"
                                class="h-11 w-full rounded-full border border-gray-200 bg-white pl-12 pr-36 text-sm sm:text-base focus:border-[var(--brand-blue,#145EFC)] focus:ring-[var(--brand-blue,#145EFC)] focus-ring focus-ring:focus"
                                placeholder="Cari FAQ… (tekan / untuk fokus)"
                                aria-label="Cari FAQ">
                            {{-- ikon kiri --}}
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400"
                                viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M10 2a8 8 0 105.293 14.293l4.207 4.207 1.414-1.414-4.207-4.207A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z" />
                            </svg>
                            {{-- area kanan --}}
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 w-36 flex items-center justify-end gap-2">
                                <kbd class="hidden sm:inline-flex h-7 items-center rounded-md border border-gray-200 bg-gray-50 px-1.5 text-[11px] text-gray-500">/</kbd>
                                <button id="btnClear" type="button"
                                    class="inline-flex h-8 items-center justify-center rounded-full px-3 text-xs text-gray-700 transition"
                                    style="background-color:#F3F4F6">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="sm:col-span-4">
                        <div class="flex w-full justify-start sm:justify-end gap-2">
                            <button id="btnOpenAll"
                                class="h-11 rounded-xl px-4 text-sm font-medium transition focus-ring focus-ring:focus"
                                style="background-color: var(--brand-red, #D21F26); color:#fff">
                                Buka Semua
                            </button>
                            <button id="btnCloseAll"
                                class="h-11 rounded-xl px-4 text-sm font-medium ring-1 ring-gray-200 bg-white text-gray-800 hover:bg-gray-50 focus-ring focus-ring:focus">
                                Tutup Semua
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Info hasil --}}
                <div class="mt-2 text-sm text-gray-500">
                    <span id="resultInfo">Menampilkan semua pertanyaan</span>
                </div>
            </div>

            {{-- DAFTAR FAQ --}}
            @php
            $faqs = [
            ['id'=>'apa-itu','q'=>'Apa itu Yayasan Kita?','a'=>'Platform donasi sosial untuk pendidikan, kemanusiaan, dan pemberdayaan ekonomi.'],
            ['id'=>'cara-donasi','q'=>'Bagaimana cara berdonasi?','a'=>'Pilih program, klik Donasi, tentukan nominal, lalu selesaikan pembayaran melalui metode yang tersedia.'],
            ['id'=>'metode-bayar','q'=>'Metode pembayaran apa saja?','a'=>'QRIS, Virtual Account, e-wallet populer, dan kartu kredit (via payment gateway).'],
            ['id'=>'transparansi','q'=>'Apakah donasi saya transparan?','a'=>'Ya, laporan penyaluran dan progres program dipublikasikan secara berkala.'],
            ['id'=>'relawan','q'=>'Bisakah menjadi relawan?','a'=>'Bisa. Daftar akun, lalu pilih program yang membuka pendaftaran relawan.'],
            ['id'=>'keamanan','q'=>'Bagaimana keamanan data & transaksi?','a'=>'Kami menggunakan TLS/HTTPS, kontrol akses, serta gateway bersertifikasi PCI DSS.'],
            ];
            @endphp

            <div id="faqList" class="mt-6 space-y-3">
                @foreach ($faqs as $item)
                <details id="{{ $item['id'] }}"
                    class="group overflow-hidden rounded-2xl ring-1 ring-gray-200 bg-white"
                    data-faq
                    data-q="{{ Str::lower($item['q'].' '.$item['a']) }}">
                    <summary class="flex items-center justify-between gap-4 cursor-pointer px-4 py-4">
                        <span class="flex min-w-0 items-start gap-3">
                            {{-- bullet merah (start brand) --}}
                            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full text-white"
                                style="background-color: var(--brand-red, #D21F26)">?</span>
                            <span class="truncate font-medium text-gray-900">{{ $item['q'] }}</span>
                        </span>
                        <span class="flex items-center gap-2">
                            {{-- copy link (biru) --}}
                            <button type="button"
                                class="hidden sm:inline-flex h-8 w-8 items-center justify-center rounded-lg ring-1 ring-gray-200 text-gray-600 hover:bg-gray-50"
                                aria-label="Salin tautan"
                                onclick="(function(id){ const u=`${location.origin}${location.pathname}#${id}`; navigator.clipboard?.writeText(u); })(`{{ $item['id'] }}`)">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color: var(--brand-blue, #145EFC);">
                                    <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                </svg>
                            </button>
                            <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180"
                                viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 14l-6-6h12l-6 6z" />
                            </svg>
                        </span>
                    </summary>
                    <div class="border-t border-gray-100 px-4 py-3 text-gray-700">
                        {{ $item['a'] }}
                    </div>
                </details>
                @endforeach
            </div>

            {{-- EMPTY STATE --}}
            <div id="emptyState"
                class="hidden mt-8 rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center">
                <div class="mx-auto h-10 w-10 rounded-full grid place-items-center"
                    style="background-color: var(--brand-blue, #145EFC)">
                    <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 2a8 8 0 105.293 14.293l4.207 4.207 1.414-1.414-4.207-4.207A8 8 0 0010 2z" />
                    </svg>
                </div>
                <p class="mt-3 text-gray-800 font-medium">Tidak ada hasil yang cocok.</p>
                <p class="mt-1 text-sm text-gray-600">
                    Coba kata kunci lain atau
                    <a href="{{ route('contact') }}" class="font-medium" style="color: var(--brand-blue, #145EFC)">hubungi kami</a>.
                </p>
            </div>

            {{-- CTA --}}
            <div class="mt-10 rounded-2xl ring-1 ring-gray-200 bg-white p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Belum menemukan jawabannya?</h3>
                    <p class="text-gray-600">Tim kami siap membantu via email atau formulir kontak.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('contact') }}"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition"
                        style="background-color: var(--brand-red, #D21F26)">
                        Hubungi Kami
                    </a>
                    <a href="{{ route('donation') }}"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold transition ring-1 ring-gray-200 text-gray-900 hover:bg-gray-50">
                        Lihat Program
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-footer />

    <script>
        // Fokus cepat: tekan "/" di mana saja
        window.addEventListener('keydown', (e) => {
            if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                document.getElementById('faqSearch')?.focus();
            }
        });

        (function() {
            const input = document.getElementById('faqSearch');
            const list = document.getElementById('faqList');
            const items = Array.from(list.querySelectorAll('[data-faq]'));
            const info = document.getElementById('resultInfo');
            const empty = document.getElementById('emptyState');
            const openAllBtn = document.getElementById('btnOpenAll');
            const closeAllBtn = document.getElementById('btnCloseAll');

            function setInfo(total, shown, q) {
                if (!q) {
                    info.textContent = 'Menampilkan semua pertanyaan';
                    return;
                }
                info.textContent = `Hasil: ${shown}/${total} untuk “${q}”`;
            }

            function filter(q) {
                const total = items.length;
                const term = (q || '').toLowerCase().trim();
                let shown = 0;
                items.forEach(d => {
                    const text = d.getAttribute('data-q') || '';
                    const ok = !term || text.includes(term);
                    d.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });
                empty.classList.toggle('hidden', shown > 0);
                setInfo(total, shown, q);
            }

            function debounce(fn, ms) {
                let t;
                return (...a) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...a), ms);
                };
            }
            const onType = debounce(() => filter(input.value), 200);
            input.addEventListener('input', onType);

            // Delegasi tombol Clear (ada di area kanan input)
            document.addEventListener('click', (e) => {
                const t = e.target.closest('#btnClear');
                if (!t) return;
                input.value = '';
                filter('');
                input.focus();
            });

            openAllBtn?.addEventListener('click', () => {
                items.forEach(d => {
                    if (d.style.display !== 'none') d.open = true;
                });
            });
            closeAllBtn?.addEventListener('click', () => {
                items.forEach(d => {
                    if (d.style.display !== 'none') d.open = false;
                });
            });

            // Buka dari hash langsung
            const h = (location.hash || '').replace('#', '');
            if (h) {
                const el = document.getElementById(h);
                if (el && el.tagName.toLowerCase() === 'details') {
                    el.open = true;
                    setTimeout(() => el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    }), 50);
                }
            }
            filter('');
        })();
    </script>
</x-app-layout>