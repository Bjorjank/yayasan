{{-- resources/views/user/donations/dashboard.blade.php --}}
<x-app-layout>
    @php
        $brandBlue = 'var(--brand-blue, #145EFC)';
        $brandRed  = 'var(--brand-red, #D21F26)';

        $fmt = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');

        $lineLabels = collect($chartSeries)->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('d M'));
        $lineData   = collect($chartSeries)->pluck('total');

        $donutLabels = collect($composition)->pluck('label');
        $donutData   = collect($composition)->pluck('value');
    @endphp

    <style>
        .hero-gradient {
            background: radial-gradient(1200px 500px at 10% -10%, rgba(20,94,252,0.20), transparent 60%),
                        radial-gradient(1000px 400px at 90% -20%, rgba(210,31,38,0.18), transparent 60%),
                        linear-gradient(180deg, #ffffff 0%, #fbfbff 100%);
        }
        .glass-card {
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.75);
            border: 1px solid rgba(20,94,252,0.08);
            box-shadow: 0 10px 30px rgba(20,94,252,0.06);
        }
        .soft-card {
            border: 1px solid rgba(0,0,0,0.06);
            background: #fff;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            border-radius: 18px;
        }
        .kpi-title { font-size: 0.72rem; letter-spacing: .08em; text-transform: uppercase; color: #6b7280 }
        .kpi-value { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.02em }
        .pill {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .35rem .7rem; border-radius: 999px; font-size: .72rem; font-weight: 600;
        }
        .table th { font-size: .8rem; color:#6b7280; text-transform: uppercase; letter-spacing:.06em }
        .table td { font-size: .95rem }
    </style>

    <section class="hero-gradient border-b border-gray-100">
        <div class="mx-auto max-w-7xl px-4 pt-8 pb-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Donasi Saya
                    </h1>
                    <p class="mt-2 text-gray-600 max-w-2xl">
                        Lihat ringkasan kontribusi, tren donasi Anda, dan riwayat transaksi yang telah terselesaikan.
                    </p>
                </div>
                <div class="flex gap-2">
                    <span class="pill" style="background:rgba(20,94,252,.10); color:#145EFC;">
                        Status Akun: Aktif
                    </span>
                    <span class="pill bg-gray-100 text-gray-700">
                        {{ auth()->user()->name }}
                    </span>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="soft-card p-5">
                    <div class="kpi-title">Hari Ini</div>
                    <div class="kpi-value text-gray-900">{{ $fmt($totals['daily']) }}</div>
                </div>
                <div class="soft-card p-5">
                    <div class="kpi-title">3 Hari</div>
                    <div class="kpi-value text-gray-900">{{ $fmt($totals['threeDay']) }}</div>
                </div>
                <div class="soft-card p-5">
                    <div class="kpi-title">Mingguan</div>
                    <div class="kpi-value text-gray-900">{{ $fmt($totals['weekly']) }}</div>
                </div>
                <div class="soft-card p-5">
                    <div class="kpi-title">Bulanan</div>
                    <div class="kpi-value text-gray-900">{{ $fmt($totals['monthly']) }}</div>
                </div>
                <div class="soft-card p-5 ring-1 ring-blue-50">
                    <div class="kpi-title">Total Sepanjang Masa</div>
                    <div class="kpi-value text-blue-700">{{ $fmt($totals['allTime']) }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Line Chart (30 hari) --}}
            <div class="soft-card p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Tren Donasi 30 Hari</h2>
                    <div class="text-xs text-gray-500">Berdasarkan tanggal <strong>paid_at</strong></div>
                </div>
                <div class="relative h-[320px]">
                    <canvas id="lineChart" height="320"></canvas>
                </div>
                @if($lineData->sum() === 0)
                    <p class="mt-4 text-sm text-gray-500">Belum ada donasi settled dalam 30 hari terakhir.</p>
                @endif
            </div>

            {{-- Donut Chart (Top Campaign) --}}
            <div class="soft-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Komposisi Campaign (Top)</h2>
                    <span class="text-xs text-gray-500">Berdasarkan nominal</span>
                </div>
                <div class="relative h-[320px]">
                    <canvas id="donutChart" height="320"></canvas>
                </div>
                @if($donutData->sum() === 0)
                    <p class="mt-4 text-sm text-gray-500">Belum ada komposisi campaign untuk ditampilkan.</p>
                @endif
            </div>
        </div>

        {{-- Riwayat Donasi --}}
        <div class="soft-card mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold">Riwayat Donasi</h2>
                <form method="GET" class="flex w-full sm:w-auto items-center gap-2">
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Cari campaign / reference / metode / order id..."
                           class="w-full sm:w-72 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <button class="rounded-xl bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-black">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto px-2 sm:px-6 py-4">
                <table class="table min-w-full">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2">Tanggal Bayar</th>
                            <th class="py-2">Campaign</th>
                            <th class="py-2">Metode</th>
                            <th class="py-2">Order/Ref</th>
                            <th class="py-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($donations as $d)
                            <tr class="border-b last:border-0">
                                <td class="py-3 text-gray-800">
                                    {{ optional($d->paid_at)->timezone(config('app.timezone'))->translatedFormat('d M Y, H:i') ?? '—' }}
                                </td>
                                <td class="py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ optional($d->campaign)->title ?? '—' }}
                                    </div>
                                    <div class="text-xs text-gray-500">Status: {{ strtoupper($d->status) }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="pill bg-gray-100 text-gray-700">{{ $d->payment_method ?? '—' }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="text-gray-700">{{ $d->order_id ?? '—' }}</div>
                                    <div class="text-xs text-gray-500">{{ $d->reference ?? $d->payment_ref ?? '' }}</div>
                                </td>
                                <td class="py-3 text-right font-semibold text-gray-900">
                                    {{ $fmt((int)$d->amount) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    Belum ada donasi settled.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $donations->links() }}
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Helper: format Rupiah untuk tooltip/ticks
        const rupiah = v => new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(v||0);

        // LINE CHART
        const lineEl = document.getElementById('lineChart');
        if (lineEl) {
            const labels = @json($lineLabels);
            const data    = @json($lineData);

            new Chart(lineEl.getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Donasi (Rp)',
                        data,
                        tension: 0.35,
                        borderWidth: 2,
                        borderColor: '#145EFC',
                        backgroundColor: 'rgba(20,94,252,0.12)',
                        pointRadius: 2.5,
                        pointHoverRadius: 4,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => 'Rp ' + rupiah(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: v => 'Rp ' + rupiah(v)
                            },
                            grid: { color: 'rgba(0,0,0,0.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // DONUT CHART
        const donutEl = document.getElementById('donutChart');
        if (donutEl) {
            const labels = @json($donutLabels);
            const data   = @json($donutData);

            new Chart(donutEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        borderWidth: 0,
                        hoverOffset: 6,
                        // palet otomatis dari Chart.js; tidak set warna spesifik
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12 } },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: Rp ${rupiah(ctx.parsed)}`
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
