<x-app-layout>
    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-blue-50 via-white to-white">
        <div class="absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full blur-3xl bg-blue-200/40"></div>
        <div class="absolute -bottom-24 -left-24 w-[420px] h-[420px] rounded-full blur-3xl bg-indigo-200/40"></div>

        <div class="mx-auto max-w-7xl px-4 pt-10 pb-6">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-600/20">
                        Tentang Yayasan
                    </span>
                    <h1 class="mt-4 text-4xl md:text-5xl font-bold leading-tight text-gray-900">
                        Bergerak <span class="text-blue-600">Memberi Dampak</span>
                    </h1>
                    <p class="mt-4 text-gray-600">
                        Fokus di pendidikan, kemanusiaan, dan pemberdayaan ekonomi. Transparansi & akuntabilitas adalah prioritas kami.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-white font-medium shadow hover:bg-blue-700 transition">
                            Lihat Program
                            <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13.5 4.5a.75.75 0 0 0 0 1.5h4.19l-9.72 9.72a.75.75 0 1 0 1.06 1.06l9.72-9.72V13.5a.75.75 0 0 0 1.5 0v-9h-9Z" />
                            </svg>
                        </a>
                        <a href="#visi-misi" class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-gray-700 font-medium ring-1 ring-gray-200 hover:bg-gray-50 transition">
                            Visi & Misi
                        </a>
                    </div>

                    {{-- Stats counters --}}
                    <div class="mt-8 grid grid-cols-3 gap-3">
                        <div class="rounded-xl bg-white ring-1 ring-gray-100 p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-gray-900 counter" data-target="250">0</div>
                            <div class="text-xs text-gray-500">Penerima</div>
                        </div>
                        <div class="rounded-xl bg-white ring-1 ring-gray-100 p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-gray-900 counter" data-target="120">0</div>
                            <div class="text-xs text-gray-500">Relawan</div>
                        </div>
                        <div class="rounded-xl bg-white ring-1 ring-gray-100 p-4 text-center shadow-sm">
                            <div class="text-2xl font-bold text-gray-900 counter" data-target="15">0</div>
                            <div class="text-xs text-gray-500">Program</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl ring-1 ring-black/5">
                        <picture>
                            <source media="(min-width: 1024px)" srcset="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=1600&auto=format&fit=crop">
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
    <section id="visi-misi" class="mx-auto max-w-7xl px-4 py-10">
        <div class="rounded-2xl bg-white ring-1 ring-gray-100 shadow-sm">
            <div class="border-b px-4">
                <nav class="flex gap-4" role="tablist">
                    <button data-tab="visi" class="tab-btn border-b-2 border-transparent py-3 text-sm font-semibold text-gray-600 aria-selected:border-blue-600 aria-selected:text-blue-700" aria-selected="true">Visi</button>
                    <button data-tab="misi" class="tab-btn border-b-2 border-transparent py-3 text-sm font-semibold text-gray-600">Misi</button>
                    <button data-tab="nilai" class="tab-btn border-b-2 border-transparent py-3 text-sm font-semibold text-gray-600">Nilai</button>
                </nav>
            </div>

            <div class="p-6">
                {{-- VISI --}}
                <div class="tab-panel" data-panel="visi">
                    <div class="grid md:grid-cols-2 gap-6 items-start">
                        <div class="rounded-xl bg-blue-50 p-6 ring-1 ring-blue-100">
                            <h3 class="text-xl font-semibold">Visi Kami</h3>
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
                                <span class="mt-2 h-2 w-2 rounded-full bg-indigo-500"></span>
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
                            <div class="font-semibold">{{ $title }}</div>
                            <p class="mt-2 text-sm text-gray-600">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== GALERI (Grid + Lightbox sederhana) ===== --}}
    <section class="mx-auto max-w-7xl px-4 py-4">
        <h3 class="text-lg font-semibold mb-3">Galeri Kegiatan</h3>
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
    <section class="mx-auto max-w-7xl px-4 py-8">
        <h3 class="text-lg font-semibold mb-4">Perjalanan Kami</h3>
        <ol class="relative border-l border-gray-200 ml-2">
            @foreach ([
            ['2023', 'Inisiatif belajar sore untuk anak sekitar.'],
            ['2024', 'Program respon bencana & kemitraan logistik.'],
            ['2025', 'Inkubasi UMKM ibu rumah tangga & ekspansi daerah.'],
            ] as [$year, $text])
            <li class="mb-8 ml-4">
                <div class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full bg-blue-500 ring-4 ring-blue-100"></div>
                <time class="mb-1 text-sm font-medium text-blue-700">{{ $year }}</time>
                <p class="text-gray-700">{{ $text }}</p>
            </li>
            @endforeach
        </ol>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-16">
        <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-semibold">Ayo Berkontribusi</h3>
                    <p class="text-white/90">Dukungan Anda membantu kami melanjutkan program yang berdampak.</p>
                </div>
                <div class="flex gap-3">
                    <a href="#" class="rounded-lg bg-white px-4 py-2.5 text-blue-700 font-semibold shadow hover:bg-blue-50 transition">Lihat Program</a>
                    <a href="#" class="rounded-lg bg-white/10 px-4 py-2.5 font-semibold ring-1 ring-white/20 hover:bg-white/20 transition">Hubungi Kami</a>
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
                    b.classList.toggle('border-blue-600', active);
                    b.classList.toggle('text-blue-700', active);
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
    <x-footer />
</x-app-layout>