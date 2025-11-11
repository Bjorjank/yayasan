{{-- resources/views/front/team.blade.php --}}
<x-app-layout>
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-10">

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
                    background-color:#D21F26;  /* merah dulu */
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
                            <x-heroicon-o-sparkles class="h-4 w-4 text-white" />
                            Terbuka untuk kolaborasi
                        </span>

                        {{-- tombol putih teks MERAH --}}
                        <a href="{{ route('contact') }}"
                            class="ml-auto inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 font-semibold hover:bg-gray-50 ring-1 ring-white/70"
                            style="color:#D21F26">
                            <x-heroicon-o-envelope class="h-4 w-4" />
                            Hubungi Kami
                        </a>
                    </div>
                </div>

                {{-- strip aksen (merah lalu biru) --}}
                <div class="w-full">
                    <div class="h-[3px] w-full" style="background-color:#D21F26"></div>
                    <div class="h-[3px] w-full" style="background-color:#145EFC"></div>
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
                                class="w-full rounded-xl border-gray-200 py-2.5 pl-11 pr-10 focus:border-[#D21F26] focus:ring-[#D21F26]"
                                autocomplete="off">
                            <x-heroicon-o-magnifying-glass
                                class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                            <button id="teamClear"
                                class="absolute right-2 top-2 hidden rounded-full p-1 text-gray-500 hover:bg-gray-100"
                                aria-label="Bersihkan pencarian">
                                <x-heroicon-o-x-mark class="h-5 w-5" />
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
                                class="team-filter inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm transition
                                           border-gray-200 bg-white text-gray-700 hover:bg-gray-50
                                           data-[active=true]:bg-[#D21F2614] data-[active=true]:border-[#D21F2640] data-[active=true]:text-[#D21F26]"
                                data-role="{{ $val }}"
                                aria-pressed="{{ $loop->first ? 'true':'false' }}"
                                {{ $loop->first ? 'data-active=true' : '' }}>
                                <span class="h-1.5 w-1.5 rounded-full {{ $loop->first ? 'bg-[#D21F26]':'bg-gray-300' }}"></span>
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- bar aksen (merah lalu biru, no gradient) --}}
                <div class="mt-4 w-full">
                    <div class="h-1 w-full rounded-full" style="background-color:#D21F26"></div>
                    <div class="mt-1 h-1 w-full rounded-full" style="background-color:#145EFC"></div>
                </div>
            </div>

            {{-- Team Grid --}}
            <div id="teamGrid" class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                ['name'=>'Arman Pratama','role'=>'Direktur Program','slug'=>'direktur-program','img'=>'photo-1511367461989-f85a21fda167','linkedin'=>'#','email'=>'#'],
                ['name'=>'Sinta Lestari','role'=>'Manajer Operasional','slug'=>'operasional','img'=>'photo-1544005313-94ddf0286df2','linkedin'=>'#','email'=>'#'],
                ['name'=>'Bima Saputra','role'=>'Koordinator Relawan','slug'=>'relawan','img'=>'photo-1527980965255-d3b416303d12','linkedin'=>'#','email'=>'#'],
                ['name'=>'Dewi Ayu','role'=>'Keuangan & Pelaporan','slug'=>'keuangan','img'=>'photo-1547425260-76bcadfb4f2','linkedin'=>'#','email'=>'#'],
                ['name'=>'Rizky Putra','role'=>'Kemitraan','slug'=>'kemitraan','img'=>'photo-1541534401786-2077eed87a6f','linkedin'=>'#','email'=>'#'],
                ['name'=>'Nadya Rahma','role'=>'Komunikasi Publik','slug'=>'komunikasi','img'=>'photo-1524504388940-b1c1722653e1','linkedin'=>'#','email'=>'#'],
                ] as $m)
                <article class="team-card group rounded-2xl bg-white p-5 ring-1 ring-gray-200 shadow-sm transition hover:shadow-md"
                    data-name="{{ Str::of($m['name'])->lower() }}"
                    data-role="{{ $m['slug'] }}">
                    <div class="aspect-square w-full overflow-hidden rounded-xl">
                        <img class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                            src="https://images.unsplash.com/{{ $m['img'] }}?q=80&w=1200&auto=format&fit=crop"
                            alt="{{ $m['name'] }}">
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $m['name'] }}</h3>
                        {{-- peran pakai BIRU (sekunder) --}}
                        <p class="text-sm" style="color:#145EFC">{{ $m['role'] }}</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex gap-2">
                            <a href="{{ $m['linkedin'] }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50"
                                aria-label="LinkedIn {{ $m['name'] }}">
                                <x-heroicon-o-link class="h-4 w-4" />
                                LinkedIn
                            </a>
                            <a href="{{ $m['email'] }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs text-gray-700 hover:bg-gray-50"
                                aria-label="Email {{ $m['name'] }}">
                                <x-heroicon-o-envelope class="h-4 w-4" />
                                Email
                            </a>
                        </div>
                        {{-- tombol aksi pakai BIRU solid (setelah merah) --}}
                        <a href="#"
                            class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-semibold text-white"
                            style="background-color:#145EFC">
                            Lihat profil
                            <x-heroicon-o-arrow-right class="h-3.5 w-3.5" />
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Empty state --}}
            <div id="teamEmpty" class="mt-12 hidden rounded-2xl border border-dashed border-gray-300 p-10 text-center">
                <div class="mx-auto h-12 w-12 rounded-full grid place-items-center text-white"
                    style="background-color:#D21F26">
                    <x-heroicon-o-magnifying-glass class="h-6 w-6 text-white" />
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Tidak ada hasil</h3>
                <p class="mt-1 text-gray-600">Coba ubah kata kunci atau filter peran.</p>
            </div>
        </div>
    </section>

    {{-- Script: Search & Filter (tanpa dependensi) --}}
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

            // Filter handlers
            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    activeRole = btn.dataset.role || 'all';
                    filterBtns.forEach(b => {
                        const active = b === btn;
                        b.dataset.active = active ? 'true' : 'false';
                        b.setAttribute('aria-pressed', active ? 'true' : 'false');
                        const dot = b.querySelector('span.h-1.5');
                        if (dot) dot.className = 'h-1.5 w-1.5 rounded-full ' + (active ? 'bg-[#D21F26]' : 'bg-gray-300');
                    });
                    apply();
                });
            });

            // Initial
            apply();
        })();
    </script>
</x-app-layout>