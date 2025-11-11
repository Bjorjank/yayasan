{{-- resources/views/front/about.blade.php --}}
<x-app-layout>
    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden bg-white">
        {{-- Aksen lembut: lingkaran blur brand --}}
        <div class="pointer-events-none absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full blur-3xl bg-brand-blue/10"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 w-[420px] h-[420px] rounded-full blur-3xl bg-brand-red/10"></div>

        {{-- Bar aksen brand di atas --}}
        <div class="w-full">
            <div class="h-[2px] w-full bg-brand-blue"></div>
            <div class="h-[2px] w-full bg-brand-red"></div>
        </div>

        <div class="mx-auto max-w-7xl px-6 pt-10 pb-6">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-brand-blue/10 px-3 py-1 text-xs font-medium text-brand-blue ring-1 ring-brand-blue/20">
                        <x-heroicon-o-building-library class="w-4 h-4" />
                        Tentang Yayasan
                    </span>

                    <h1 class="mt-4 text-4xl md:text-5xl font-bold leading-tight text-gray-900">
                        Bergerak <span class="text-brand-blue">Memberi Dampak</span>
                    </h1>
                    <p class="mt-4 text-gray-600">
                        Fokus di pendidikan, kemanusiaan, dan pemberdayaan ekonomi. Transparansi & akuntabilitas adalah prioritas kami.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        {{-- Tombol utama = MERAH --}}
                        <a href="{{ route('donation') }}"
                            class="inline-flex items-center rounded-lg bg-brand-red px-4 py-2.5 text-white font-medium shadow hover:brightness-95 transition">
                            <x-heroicon-o-rocket-launch class="w-5 h-5 mr-2 text-white/90" />
                            Lihat Program
                        </a>
                        {{-- Tombol sekunder = putih outline biru --}}
                        <a href="#visi-misi"
                            class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-gray-700 font-medium ring-1 ring-brand-blue/30 hover:bg-gray-50 transition">
                            <x-heroicon-o-eye class="w-5 h-5 mr-2 text-brand-blue" />
                            Visi & Misi
                        </a>
                    </div>

                    {{-- Stats counters --}}
                    <div class="mt-8 grid grid-cols-3 gap-3">
                        <div class="rounded-xl bg-white ring-1 ring-gray-100 p-4 text-center shadow-sm">
                            <div class="mx-auto mb-1 h-8 w-8 rounded-full bg-brand-blue/10 grid place-items-center">
                                <x-heroicon-o-users class="w-5 h-5 text-brand-blue" />
                            </div>
                            <div class="text-2xl font-bold text-gray-900 counter" data-target="250">0</div>
                            <div class="text-xs text-gray-500">Penerima</div>
                        </div>

                        <div class="rounded-xl bg-white ring-1 ring-gray-100 p-4 text-center shadow-sm">
                            <div class="mx-auto mb-1 h-8 w-8 rounded-full bg-brand-red/10 grid place-items-center">
                                <x-heroicon-o-hand-thumb-up class="w-5 h-5 text-brand-red" />
                            </div>
                            <div class="text-2xl font-bold text-gray-900 counter" data-target="120">0</div>
                            <div class="text-xs text-gray-500">Relawan</div>
                        </div>

                        <div class="rounded-xl bg-white ring-1 ring-gray-100 p-4 text-center shadow-sm">
                            <div class="mx-auto mb-1 h-8 w-8 rounded-full bg-brand-blue/10 grid place-items-center">
                                <x-heroicon-o-rectangle-stack class="w-5 h-5 text-brand-blue" />
                            </div>
                            <div class="text-2xl font-bold text-gray-900 counter" data-target="15">0</div>
                            <div class="text-xs text-gray-500">Program</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl ring-1 ring-black/5">
                        <picture>
                            <source media="(min-width: 1024px)"
                                srcset="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1600&auto=format&fit=crop">
                            <img src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1200&auto=format&fit=crop"
                                alt="Kegiatan sosial yayasan" class="h-[380px] w-full object-cover">
                        </picture>
                    </div>
                    <div class="absolute -bottom-6 -left-6 hidden md:block rounded-xl overflow-hidden ring-4 ring-white shadow-xl">
                        <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=800&auto=format&fit=crop"
                            alt="Belajar bersama" class="h-28 w-44 object-cover">
                    </div>
                    <div class="absolute -top-6 -right-6 hidden md:block rounded-xl overflow-hidden ring-4 ring-white shadow-xl">
                        <img src="https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?q=80&w=800&auto=format&fit=crop"
                            alt="Relawan berbagi" class="h-24 w-40 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== VISI & MISI (Tab Interaktif) ===== --}}
    <section id="visi-misi" class="mx-auto max-w-7xl px-6 py-10">
        <div class="rounded-2xl bg-white ring-1 ring-gray-100 shadow-sm overflow-hidden">
            <div class="border-b px-4">
                <nav class="flex gap-4" role="tablist">
                    {{-- Tab aktif = border brand-blue, teks brand-blue --}}
                    <button data-tab="visi"
                        class="tab-btn border-b-2 border-transparent py-3 text-sm font-semibold text-gray-600 aria-selected:border-brand-blue aria-selected:text-brand-blue"
                        aria-selected="true">
                        <span class="inline-flex items-center gap-2">
                            <x-heroicon-o-light-bulb class="w-5 h-5" />
                            Visi
                        </span>
                    </button>
                    <button data-tab="misi"
                        class="tab-btn border-b-2 border-transparent py-3 text-sm font-semibold text-gray-600">
                        <span class="inline-flex items-center gap-2">
                            <x-heroicon-o-flag class="w-5 h-5" />
                            Misi
                        </span>
                    </button>
                    <button data-tab="nilai"
                        class="tab-btn border-b-2 border-transparent py-3 text-sm font-semibold text-gray-600">
                        <span class="inline-flex items-center gap-2">
                            <x-heroicon-o-heart class="w-5 h-5" />
                            Nilai
                        </span>
                    </button>
                </nav>
            </div>

            <div class="p-6">
                {{-- VISI --}}
                <div class="tab-panel" data-panel="visi">
                    <div class="grid md:grid-cols-2 gap-6 items-start">
                        <div class="rounded-xl bg-brand-blue/10 p-6 ring-1 ring-brand-blue/20">
                            <h3 class="text-xl font-semibold flex items-center gap-2">
                                <x-heroicon-o-sparkles class="w-5 h-5 text-brand-blue" />
                                Visi Kami
                            </h3>
                            <p class="mt-3 text-gray-700">
                                Masyarakat berdaya, sejahtera, dan peduli—didorong oleh pendidikan inklusif,
                                respon kemanusiaan yang cepat, serta pemberdayaan ekonomi berkelanjutan.
                            </p>
                        </div>
                        <img class="rounded-xl h-52 w-full object-cover ring-1 ring-gray-100"
                            src="https://images.unsplash.com/photo-1535378917042-10a22c95931a?q=80&w=1200&auto=format&fit=crop"
                            alt="Visi yayasan">
                    </div>
                </div>

                {{-- MISI --}}
                <div class="tab-panel hidden" data-panel="misi">
                    <div class="grid md:grid-cols-2 gap-6">
                        <ul class="space-y-4">
                            @foreach ([
                            'Program beasiswa & literasi untuk anak dan remaja.',
                            'Respon bencana: logistik, layanan kesehatan, dukungan psikososial.',
                            'Pemberdayaan UMKM: pelatihan, modal mikro, pendampingan bisnis.',
                            'Transparansi & akuntabilitas melalui pelaporan berkala.',
                            ] as $m)
                            <li class="flex gap-3">
                                <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 rounded-full bg-brand-red/10 ring-1 ring-brand-red/20 items-center justify-center">
                                    <x-heroicon-o-check class="w-4 h-4 text-brand-red" />
                                </span>
                                <span class="text-gray-700">{{ $m }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <img class="rounded-xl h-52 w-full object-cover ring-1 ring-gray-100"
                            src="https://images.unsplash.com/photo-1516822003754-cca485356ecb?q=80&w=1200&auto=format&fit=crop"
                            alt="Misi yayasan">
                    </div>
                </div>

                {{-- NILAI --}}
                <div class="tab-panel hidden" data-panel="nilai">
                    <div class="grid md:grid-cols-3 gap-4">
                        @foreach ([
                        ['Integritas', 'Pengelolaan dana publik yang akuntabel & transparan.'],
                        ['Kolaborasi', 'Bekerja bersama relawan, komunitas, dan mitra.'],
                        ['Empati', 'Menempatkan penerima manfaat di pusat keputusan.'],
                        ] as [$title, $desc])
                        <div class="rounded-xl bg-white p-5 ring-1 ring-gray-100 shadow-sm">
                            <div class="font-semibold flex items-center gap-2">
                                @if ($title === 'Integritas')
                                <x-heroicon-o-shield-check class="w-5 h-5 text-brand-blue" />
                                @elseif ($title === 'Kolaborasi')
                                <x-heroicon-o-user-group class="w-5 h-5 text-brand-red" />
                                @else
                                <x-heroicon-o-hand-raised class="w-5 h-5 text-brand-blue" />
                                @endif
                                {{ $title }}
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== GALERI (Grid + Lightbox) ===== --}}
    <section class="mx-auto max-w-7xl px-6 py-4">
        <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
            <x-heroicon-o-photo class="w-5 h-5 text-brand-blue" />
            Galeri Kegiatan
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php
            $imgs = [
            'photo-1529156069898-49953e39b3ac',
            'photo-1496307042754-b4aa456c4a2d',
            'photo-1535378917042-10a22c95931a',
            'photo-1511988617509-a57c8a288659',
            'photo-1542810634-71277d95dcbb',
            'photo-1469474968028-56623f02e42e',
            'photo-1506784365847-bbad939e9335',
            'photo-1516822003754-cca485356ecb',
            ];
            @endphp
            @foreach ($imgs as $seed)
            <button type="button"
                class="group relative overflow-hidden rounded-xl ring-1 ring-gray-100 bg-white focus:outline-none"
                data-lightbox="https://images.unsplash.com/{{ $seed }}?q=80&w=1600&auto=format&fit=crop">
                <img
                    src="https://images.unsplash.com/{{ $seed }}?q=80&w=900&auto=format&fit=crop"
                    alt="Kegiatan yayasan"
                    class="h-36 w-full object-cover transition duration-300 group-hover:scale-105">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            </button>
            @endforeach
        </div>
    </section>

    {{-- ===== TIMELINE ===== --}}
    <section class="mx-auto max-w-7xl px-6 py-8">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <x-heroicon-o-calendar-days class="w-5 h-5 text-brand-blue" />
            Perjalanan Kami
        </h3>
        <ol class="relative border-l border-gray-200 ml-2">
            @foreach ([
            ['2023', 'Inisiatif belajar sore untuk anak sekitar.'],
            ['2024', 'Program respon bencana & kemitraan logistik.'],
            ['2025', 'Inkubasi UMKM ibu rumah tangga & ekspansi daerah.'],
            ] as [$year, $text])
            @php $isEven = ((int)$year % 2) === 0; @endphp
            <li class="mb-8 ml-4 relative">
                <div
                    class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full {{ $isEven ? 'bg-brand-red ring-4 ring-brand-red/20' : 'bg-brand-blue ring-4 ring-brand-blue/20' }}">
                </div>
                <time class="mb-1 text-sm font-medium {{ $isEven ? 'text-brand-red' : 'text-brand-blue' }}">
                    {{ $year }}
                </time>
                <p class="text-gray-700">{{ $text }}</p>
            </li>
            @endforeach
        </ol>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="mx-auto max-w-7xl px-6 pb-16">
        <div class="rounded-2xl bg-brand-red px-6 py-8 text-white shadow-lg ring-1 ring-brand-red/30">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-semibold flex items-center gap-2">
                        <x-heroicon-o-megaphone class="w-6 h-6 text-white/90" />
                        Ayo Berkontribusi
                    </h3>
                    <p class="text-white/90">Dukungan Anda membantu kami melanjutkan program yang berdampak.</p>
                </div>
                <div class="flex gap-3">
                    {{-- Sekunder putih = menonjol di atas merah, teks biru brand --}}
                    <a href="{{ route('donation') }}"
                        class="rounded-lg bg-white px-4 py-2.5 text-brand-blue font-semibold shadow hover:bg-gray-50 transition inline-flex items-center">
                        <x-heroicon-o-rectangle-stack class="w-5 h-5 mr-2 text-brand-blue/90" />
                        Lihat Program
                    </a>
                    {{-- Ghost putih tipis --}}
                    <a href="{{ route('contact') }}"
                        class="rounded-lg bg-white/10 px-4 py-2.5 font-semibold ring-1 ring-white/30 hover:bg-white/20 transition inline-flex items-center">
                        <x-heroicon-o-paper-airplane class="w-5 h-5 mr-2 text-white/90" />
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Scripts interaktif kecil ===== --}}
    <script>
        // Counter animasi saat terlihat
        (function() {
            const els = document.querySelectorAll('.counter');
            const obs = new IntersectionObserver((entries, o) => {
                entries.forEach(e => {
                    if (!e.isIntersecting) return;
                    const el = e.target;
                    const target = parseInt(el.dataset.target || '0', 10);
                    let current = 0;
                    const duration = 900; // ms
                    const start = performance.now();

                    function tick(t) {
                        const p = Math.min((t - start) / duration, 1);
                        current = Math.floor(target * (0.2 + 0.8 * p));
                        el.textContent = current.toLocaleString('id-ID');
                        if (p < 1) requestAnimationFrame(tick);
                        else o.unobserve(el);
                    }
                    requestAnimationFrame(tick);
                });
            }, {
                threshold: 0.3
            });
            els.forEach(el => obs.observe(el));
        })();

        // Tabs Visi/Misi/Nilai
        (function() {
            const btns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');

            function activate(name) {
                btns.forEach(b => {
                    const active = b.dataset.tab === name;
                    b.setAttribute('aria-selected', active ? 'true' : 'false');
                    b.classList.toggle('border-brand-blue', active);
                    b.classList.toggle('text-brand-blue', active);
                });
                panels.forEach(p => {
                    p.classList.toggle('hidden', p.dataset.panel !== name);
                });
            }
            btns.forEach(b => b.addEventListener('click', () => activate(b.dataset.tab)));
            activate('visi'); // default
        })();

        // Lightbox sederhana untuk galeri
        (function() {
            const buttons = document.querySelectorAll('[data-lightbox]');
            let modal;

            function open(src) {
                if (!modal) {
                    modal = document.createElement('div');
                    modal.className = 'fixed inset-0 z-50 hidden';
                    modal.innerHTML = `
                        <div class="absolute inset-0 bg-black/70"></div>
                        <div class="relative mx-auto max-w-4xl px-4 py-10 flex h-full items-center justify-center">
                            <img class="max-h-[80vh] w-auto rounded-xl shadow-2xl ring-1 ring-white/10" src="" alt="Preview">
                            <button type="button" class="absolute top-6 right-6 rounded-md bg-white/10 px-3 py-1.5 text-white ring-1 ring-white/20 hover:bg-white/20">Tutup</button>
                        </div>`;
                    document.body.appendChild(modal);
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal || e.target.tagName === 'BUTTON') modal.classList.add('hidden');
                    });
                }
                modal.querySelector('img').src = src;
                modal.classList.remove('hidden');
            }
            buttons.forEach(b => b.addEventListener('click', () => open(b.dataset.lightbox)));
        })();
    </script>

    {{-- Footer global dipanggil dari layout utama --}}
    <x-footer />
</x-app-layout>