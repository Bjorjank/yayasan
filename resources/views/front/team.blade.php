{{-- resources/views/front/team.blade.php --}}
<x-app-layout>
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-10">

            {{-- Scoped, CSS aman (tanpa arbitrary, tanpa ruleset kosong) --}}
            <style>
                :root {
                    --brand-red: #D21F26;
                    --brand-blue: #145EFC;
                }

                /* Tombol filter: state aktif pakai class .active, bukan data-variant */
                .team-filter {
                    transition: background-color .2s, border-color .2s, color .2s
                }

                .team-filter .dot {
                    width: 0.375rem;
                    height: 0.375rem;
                    border-radius: 9999px;
                    background: #D1D5DB
                }

                .team-filter.active {
                    background: rgba(210, 31, 38, .08);
                    border-color: rgba(210, 31, 38, .4);
                    color: var(--brand-red)
                }

                .team-filter.active .dot {
                    background: var(--brand-red)
                }
            </style>

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <span class="mx-1.5">/</span>
                <span class="text-gray-700 font-medium">Tim Kami</span>
            </nav>

            {{-- Hero (solid RED, no gradient) --}}
            <header
                class="mt-4 overflow-hidden rounded-3xl text-white ring-1 shadow-sm"
                style="
                    background-color: var(--brand-red);
                    border-color: rgba(210,31,38,.25);
                    box-shadow: 0 10px 30px rgba(210,31,38,.18);
                ">
                <div class="relative px-8 py-10 md:px-12 md:py-12">
                    <div class="absolute -right-16 -top-16 h-56 w-56 bg-white/10 rounded-full blur-2xl"></div>

                    <h1 class="text-3xl md:text-4xl font-bold">Tim Kami</h1>
                    <p class="mt-2 max-w-2xl text-white/90">
                        Kolaborasi pekerja sosial, pendidik, dan profesional untuk mendorong lebih banyak dampak baik.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-3 text-sm">
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 ring-1"
                            style="background-color: rgba(255,255,255,.10); border-color: rgba(255,255,255,.25)">
                            {{-- sparkles icon --}}
                            <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2l1.5 3.8L17 7l-3.5 1.2L12 12l-1.5-3.8L7 7l3.5-1.2L12 2zm6 10l1 2.5 2.5 1-2.5 1L18 19l-1-2.5-2.5-1 2.5-1L18 12zM6 12l1 2.5 2.5 1-2.5 1L6 19l-1-2.5-2.5-1 2.5-1L6 12z" />
                            </svg>
                            Terbuka untuk kolaborasi
                        </span>

                        {{-- tombol putih teks MERAH --}}
                        <a href="{{ route('contact') }}"
                            class="ml-auto inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold hover:bg-gray-50 ring-1 ring-white/70"
                            style="color: var(--brand-red)">
                            {{-- envelope icon --}}
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v.4l-10 6-10-6V6zm0 2.9l9.4 5.6a1.5 1.5 0 001.2 0L22 8.9V18a2 2 0 01-2 2H4a2 2 0 01-2-2V8.9z" />
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>
                </div>

                {{-- strip aksen (merah lalu biru) --}}
                <div class="w-full">
                    <div class="h-[3px] w-full" style="background-color: var(--brand-red)"></div>
                    <div class="h-[3px] w-full" style="background-color: var(--brand-blue)"></div>
                </div>
            </header>

            {{-- Controls: Search & Filter --}}
            <div class="mt-8 rounded-2xl bg-white p-4 ring-1 ring-gray-200 shadow-sm">
                <div class="grid gap-4 md:grid-cols-12 md:items-center">
                    {{-- Search --}}
                    <div class="md:col-span-7">
                        <label for="teamSearch" class="sr-only">Cari anggota tim</label>
                        <div class="relative">
                            <input id="teamSearch" type="text"
                                placeholder="Cari nama atau peran…"
                                class="w-full rounded-xl border-gray-200 py-2.5 pl-11 pr-10 focus:border-red-600 focus:ring-2 focus:ring-red-600/30"
                                autocomplete="off">
                            <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M10 2a8 8 0 105.293 14.293l4.207 4.207 1.414-1.414-4.207-4.207A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z" />
                            </svg>
                            <button id="teamClear"
                                class="absolute right-2 top-2 hidden rounded-full p-1 text-gray-500 hover:bg-gray-100"
                                aria-label="Bersihkan pencarian">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M18.3 5.71L12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.3 19.71 2.89 18.3 9.17 12 2.89 5.71 4.3 4.29l6.29 6.3 6.29-6.3z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Filters (roles) --}}
                    <div class="md:col-span-5">
                        <div class="flex flex-wrap gap-2 md:justify-end">
                            @php
                            $filters = [
                            ['all','Semua'],
                            ['direktur-program','Direktur Program'],
                            ['operasional','Operasional'],
                            ['relawan','Relawan'],
                            ['keuangan','Keuangan'],
                            ['kemitraan','Kemitraan'],
                            ['komunikasi','Komunikasi'],
                            ];
                            @endphp
                            @foreach($filters as [$val,$label])
                            <button type="button"
                                class="team-filter inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm border-gray-200 bg-white text-gray-700 hover:bg-gray-50 {{ $loop->first ? 'active' : '' }}"
                                data-role="{{ $val }}"
                                aria-pressed="{{ $loop->first ? 'true':'false' }}">
                                <span class="dot"></span>
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- bar aksen (merah lalu biru, no gradient) --}}
                <div class="mt-4 w-full">
                    <div class="h-1 w-full rounded-full" style="background-color: var(--brand-red)"></div>
                    <div class="mt-1 h-1 w-full rounded-full" style="background-color: var(--brand-blue)"></div>
                </div>
            </div>

            {{-- Team Grid --}}
            <div id="teamGrid" class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                ['name'=>'Arman Pratama','role'=>'Direktur Program','slug'=>'direktur-program','img'=>'photo-1511367461989-f85a21fda167','linkedin'=>'#','email'=>'#'],
                ['name'=>'Sinta Lestari','role'=>'Manajer Operasional','slug'=>'operasional','img'=>'photo-1544005313-94ddf0286df2','linkedin'=>'#','email'=>'#'],
                ['name'=>'Bima Saputra','role'=>'Koordinator Relawan','slug'=>'relawan','img'=>'photo-1527980965255-d3b416303d12','linkedin'=>'#','email'=>'#'],
                ['name'=>'Dewi Ayu','role'=>'Keuangan & Pelaporan','slug'=>'keuangan','img'=>'photo-1554151228-14d9def656e4','linkedin'=>'#','email'=>'#'],
                ['name'=>'Rizky Putra','role'=>'Kemitraan','slug'=>'kemitraan','img'=>'photo-1500648767791-00dcc994a43e','linkedin'=>'#','email'=>'#'],
                ['name'=>'Nadya Rahma','role'=>'Komunikasi Publik','slug'=>'komunikasi','img'=>'photo-1524504388940-b1c1722653e1','linkedin'=>'#','email'=>'#'],
                ] as $m)
                <article class="team-card group rounded-2xl bg-white p-5 ring-1 ring-gray-200 shadow-sm transition hover:shadow-md"
                    data-name="{{ \Illuminate\Support\Str::of($m['name'])->lower() }}"
                    data-role="{{ $m['slug'] }}">
                    <div class="aspect-square w-full overflow-hidden rounded-xl">
                        <img class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                            src="https://images.unsplash.com/{{ $m['img'] }}?q=80&w=1200&auto=format&fit=crop"
                            alt="{{ $m['name'] }}">
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $m['name'] }}</h3>
                        <p class="text-sm" style="color: var(--brand-blue)">{{ $m['role'] }}</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex gap-2">
                            <a href="{{ $m['linkedin'] }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50"
                                aria-label="LinkedIn {{ $m['name'] }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM0 8h5v16H0V8zm7.5 0h4.8v2.2h.1c.7-1.2 2.4-2.5 5-2.5 5.3 0 6.3 3.5 6.3 8v8.3h-5V16c0-1.9 0-4.3-2.6-4.3-2.6 0-3 2-3 4.1v8.2h-5V8z" />
                                </svg>
                                LinkedIn
                            </a>
                            <a href="{{ $m['email'] }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50"
                                aria-label="Email {{ $m['name'] }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v.4l-10 6-10-6V6zm0 2.9l9.4 5.6a1.5 1.5 0 001.2 0L22 8.9V18a2 2 0 01-2 2H4a2 2 0 01-2-2V8.9z" />
                                </svg>
                                Email
                            </a>
                        </div>
                        <a href="#"
                            class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-semibold text-white"
                            style="background-color: var(--brand-blue)">
                            Lihat profil
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M10 17l5-5-5-5v10z" />
                            </svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Empty state --}}
            <div id="teamEmpty" class="mt-12 hidden rounded-2xl border border-dashed border-gray-300 p-10 text-center">
                <div class="mx-auto h-12 w-12 rounded-full grid place-items-center text-white"
                    style="background-color: var(--brand-red)">
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M10 2a8 8 0 105.293 14.293l4.207 4.207-1.414 1.414-4.207-4.207A8 8 0 0010 2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Tidak ada hasil</h3>
                <p class="mt-1 text-gray-600">Coba ubah kata kunci atau filter peran.</p>
            </div>
        </div>
    </section>

    <x-footer />

    {{-- Script: Search & Filter (tanpa arbitrary & variant data) --}}
    <script>
        (function() {
            const q = document.getElementById('teamSearch');
            const clearBtn = document.getElementById('teamClear');
            const grid = document.getElementById('teamGrid');
            const cards = Array.from(grid.querySelectorAll('.team-card'));
            const filterBtns = Array.from(document.querySelectorAll('.team-filter'));
            const empty = document.getElementById('teamEmpty');

            let activeRole = 'all';

            function normalize(s) {
                return (s || '').toString().toLowerCase().trim();
            }

            function apply() {
                const term = normalize(q.value);
                let visible = 0;

                cards.forEach(card => {
                    const name = card.dataset.name || '';
                    const role = card.dataset.role || '';
                    const matchText = !term || name.includes(term) || role.includes(term);
                    const matchRole = activeRole === 'all' || role === activeRole;
                    const show = matchText && matchRole;
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                empty.classList.toggle('hidden', visible !== 0);
                clearBtn.classList.toggle('hidden', q.value.length === 0);
            }

            // Search handlers
            q.addEventListener('input', apply);
            clearBtn.addEventListener('click', () => {
                q.value = '';
                q.focus();
                apply();
            });

            // Filter handlers (pakai class .active, bukan data-variant)
            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    activeRole = btn.dataset.role || 'all';
                    filterBtns.forEach(b => {
                        const isActive = b === btn;
                        b.classList.toggle('active', isActive);
                        b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });
                    apply();
                });
            });

            // Initial
            apply();
        })();
    </script>
</x-app-layout>