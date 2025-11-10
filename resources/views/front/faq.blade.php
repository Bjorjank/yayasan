{{-- resources/views/front/faq.blade.php --}}
<x-app-layout>
    <style>
        details>summary::-webkit-details-marker {
            display: none;
        }

        details>summary {
            list-style: none;
        }
    </style>

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-12">

            {{-- HEADER --}}
            <header class="max-w-3xl">
                <p class="text-xs font-medium tracking-wide text-blue-700/80 uppercase">FAQ</p>
                <h1 class="mt-2 text-3xl md:text-4xl font-bold leading-tight text-gray-900">Pertanyaan yang Sering Diajukan</h1>
                <p class="mt-3 text-gray-600">Jawaban singkat tentang donasi, relawan, akun, dan keamanan. Jika belum ketemu, hubungi kami.</p>
            </header>

            {{-- BAR UTILITAS: SEARCH (kiri) + TOMBOL (kanan) --}}
            <div class="mt-6">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">

                    {{-- SEARCH WRAPPER --}}
                    <div class="sm:col-span-8">
                        <div class="relative h-11">
                            {{-- input: padding kanan disesuaikan dengan lebar area tombol (w-32) + gap --}}
                            <input
                                id="faqSearch"
                                type="search"
                                class="h-11 w-full rounded-full border border-gray-200 bg-white pl-12 pr-36 text-sm sm:text-base focus:border-blue-400 focus:ring-blue-400"
                                placeholder="Cari FAQ… (tekan / untuk fokus)"
                                aria-label="Cari FAQ">

                            {{-- ikon kiri (selalu center vertikal) --}}
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M10 2a8 8 0 105.293 14.293l4.207 4.207 1.414-1.414-4.207-4.207A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z" />
                            </svg>

                            {{-- AREA KANAN: lebar tetap, sejajar rapi --}}
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 w-32 flex items-center justify-end gap-2">
                                <kbd class="hidden sm:inline-flex h-7 items-center rounded-md border border-gray-200 bg-gray-50 px-1.5 text-[11px] text-gray-500">/</kbd>
                                <button
                                    id="btnClear"
                                    type="button"
                                    class="inline-flex h-8 items-center justify-center rounded-full bg-gray-100 px-3 text-xs text-gray-600 hover:bg-gray-200">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="sm:col-span-4">
                        <div class="flex w-full justify-start sm:justify-end gap-2">
                            <button id="btnOpenAll" class="h-11 rounded-xl ring-1 ring-gray-200 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50">Buka Semua</button>
                            <button id="btnCloseAll" class="h-11 rounded-xl ring-1 ring-gray-200 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50">Tutup Semua</button>
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
                <details id="{{ $item['id'] }}" class="group overflow-hidden rounded-2xl ring-1 ring-gray-200 bg-white" data-faq
                    data-q="{{ Str::lower($item['q'].' '.$item['a']) }}">
                    <summary class="flex items-center justify-between gap-4 cursor-pointer px-4 py-4 [&::-webkit-details-marker]:hidden">
                        <span class="flex min-w-0 items-start gap-3">
                            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-blue-50 text-blue-600">?</span>
                            <span class="truncate font-medium text-gray-900">{{ $item['q'] }}</span>
                        </span>
                        <span class="flex items-center gap-2">
                            <button type="button"
                                class="hidden sm:inline-flex h-8 w-8 items-center justify-center rounded-lg ring-1 ring-gray-200 text-gray-500 hover:bg-gray-50"
                                aria-label="Salin tautan"
                                onclick="(function(id){ const u=`${location.origin}${location.pathname}#${id}`; navigator.clipboard?.writeText(u); })(`{{ $item['id'] }}`)">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M3 8a5 5 0 015-5h3v2H8a3 3 0 100 6h3v2H8a5 5 0 01-5-5zm13-3h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z" />
                                </svg>
                            </button>
                            <svg class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 14l-6-6h12l-6 6z" />
                            </svg>
                        </span>
                    </summary>
                    <div class="border-t border-gray-100 px-4 py-3 text-gray-700">{{ $item['a'] }}</div>
                </details>
                @endforeach
            </div>

            {{-- EMPTY STATE --}}
            <div id="emptyState" class="hidden mt-8 rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center">
                <p class="text-gray-700 font-medium">Tidak ada hasil yang cocok.</p>
                <p class="mt-1 text-sm text-gray-600">
                    Coba kata kunci lain atau <a href="{{ route('contact') }}" class="text-blue-700 hover:underline">hubungi kami</a>.
                </p>
            </div>

            {{-- CTA --}}
            <div class="mt-10 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold">Belum menemukan jawabannya?</h3>
                    <p class="text-white/90">Tim kami siap membantu via email atau formulir kontak.</p>
                </div>
                <a href="{{ route('contact') }}" class="rounded-xl bg-white text-blue-700 px-4 py-2 font-semibold hover:bg-blue-50">Hubungi Kami</a>
            </div>

        </div>
    </section>

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
            const clear = document.getElementById('btnClear'); // akan null jika id tak ada; aman
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
                    const text = d.getAttribute('data-q');
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

            // tombol Clear yang sekarang berada di area kanan, kliknya kita delegasikan:
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

            // Buka dari hash
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