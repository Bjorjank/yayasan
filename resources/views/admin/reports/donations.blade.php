@extends('layouts.admin')

@section('title', 'Admin — Laporan Donasi')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div x-data="reportPage" x-init="init(@js($range), @js($chartLabels), @js($chartData))" class="space-y-6">

  {{-- Header --}}
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-black text-gray-900">Laporan Donasi</h1>
      <p class="text-gray-600">Ringkasan transaksi lintas campaign (admin).</p>
      @if($start && $end)
        <p class="text-xs text-gray-500 mt-1">Periode: {{ $start->format('d M Y') }} – {{ $end->format('d M Y') }}</p>
      @else
        <p class="text-xs text-gray-500 mt-1">Periode: Semua waktu</p>
      @endif
    </div>
    <form method="get" class="flex flex-wrap items-center gap-2">
      @php $ranges = ['today'=>'Hari ini','3d'=>'3 Hari','week'=>'Minggu ini','month'=>'Bulan ini','all'=>'Semua']; @endphp

      <select name="range" class="border rounded-xl px-3 py-2 text-sm">
        @foreach($ranges as $k=>$label)
          <option value="{{ $k }}" @selected($range===$k)>{{ $label }}</option>
        @endforeach
      </select>

      {{-- Quick select campaign --}}
      <select onchange="if(this.value){window.location=this.value}" class="border rounded-xl px-3 py-2 text-sm">
        <option value="">— Lihat per Campaign —</option>
        @foreach($campaignOptions as $opt)
          <option value="{{ route('admin.reports.donations.campaign', $opt) }}"
            @if(isset($campaign) && $campaign->id===$opt->id) selected @endif>
            {{ $opt->title }}
          </option>
        @endforeach
      </select>

      @if(isset($campaign))
        <a href="{{ route('admin.reports.donations') }}" class="px-3 py-2 rounded-xl ring-1 ring-gray-200 bg-white hover:bg-gray-50 text-sm">
          Semua Campaign
        </a>
      @endif

      <button class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm hover:bg-blue-700">Terapkan</button>
    </form>
  </div>

  {{-- Cards ringkasan --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">Total Nominal</div>
      <div class="mt-1 text-2xl font-bold">Rp {{ number_format($totalAmount,0,',','.') }}</div>
      <div class="mt-2 text-xs text-gray-500">Transaksi: <span class="font-medium text-gray-700">{{ number_format($totalCount) }}</span></div>
    </div>
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">Hari ini</div>
      <div class="mt-1 text-lg font-bold">Rp {{ number_format($quick['today']['amount'] ?? 0,0,',','.') }}</div>
      <div class="text-xs text-gray-500">Transaksi: {{ number_format($quick['today']['count'] ?? 0) }}</div>
    </div>
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">3 Hari</div>
      <div class="mt-1 text-lg font-bold">Rp {{ number_format($quick['3d']['amount'] ?? 0,0,',','.') }}</div>
      <div class="text-xs text-gray-500">Transaksi: {{ number_format($quick['3d']['count'] ?? 0) }}</div>
    </div>
    <div class="rounded-2xl bg-white/80 ring-1 ring-gray-200 p-5 shadow-sm">
      <div class="text-xs text-gray-500">Bulan ini</div>
      <div class="mt-1 text-lg font-bold">Rp {{ number_format($quick['month']['amount'] ?? 0,0,',','.') }}</div>
      <div class="text-xs text-gray-500">Transaksi: {{ number_format($quick['month']['count'] ?? 0) }}</div>
    </div>
  </div>

  {{-- Chart --}}
  <div class="rounded-3xl bg-white/80 ring-1 ring-gray-200 p-6 shadow-sm">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-semibold text-gray-900">
        Total Harian {{ isset($campaign) ? '— '.$campaign->title : '' }}
      </h3>
      <span class="text-xs text-gray-500">Nominal per hari</span>
    </div>
    <div class="mt-4">
      <div class="w-full overflow-x-auto">
        <div class="min-w-[600px]">
          <div class="flex items-end gap-2 h-40">
            <template x-for="(v,i) in chart.data" :key="i">
              <div class="flex flex-col items-center gap-1">
                <div class="w-6 bg-blue-600 rounded-t" :style="`height:${chart.scale(v)}px`" :title="`Rp ${chart.format(v)}`"></div>
                <div class="text-[10px] text-gray-500" x-text="chart.labels[i]"></div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Top Campaigns --}}
  <div class="rounded-3xl bg-white/80 ring-1 ring-gray-200 p-6 shadow-sm">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-semibold text-gray-900">Top Campaign (Nominal)</h3>
      <a href="{{ route('admin.campaigns.index') }}" class="text-xs text-blue-700 hover:text-blue-900">Kelola Campaign</a>
    </div>
    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
      @forelse($topCampaigns as $row)
        <a href="{{ route('campaign.show', $row->campaign->slug) }}" target="_blank"
           class="rounded-2xl ring-1 ring-gray-200 p-4 hover:bg-gray-50">
          <div class="text-sm font-semibold text-gray-900">{{ $row->campaign->title ?? '—' }}</div>
          <div class="text-xs text-gray-500 mt-1">Transaksi: {{ number_format($row->cnt) }}</div>
          <div class="text-base font-bold mt-1">Rp {{ number_format((int)$row->total,0,',','.') }}</div>
        </a>
      @empty
        <div class="text-sm text-gray-500">Belum ada data.</div>
      @endforelse
    </div>
  </div>

  {{-- Tabel donasi --}}
  <div class="rounded-3xl overflow-hidden ring-1 ring-gray-200 bg-white shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
      <div class="text-sm text-gray-600">
        Menampilkan <span class="font-semibold">{{ $donations->count() }}</span> dari
        <span class="font-semibold">{{ $donations->total() }}</span> donasi
        @if(isset($campaign))
          — <span class="text-gray-700">Campaign:</span> <span class="font-semibold">{{ $campaign->title }}</span>
        @endif
      </div>
      <div class="text-xs text-gray-500">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="p-3 text-left">Waktu</th>
            <th class="p-3 text-left">Donatur</th>
            <th class="p-3 text-left">Kontak</th>
            <th class="p-3 text-left">Campaign</th>
            <th class="p-3 text-right">Nominal</th>
            <th class="p-3 text-left">Order ID</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($donations as $d)
            <tr class="hover:bg-gray-50/60">
              <td class="p-3 text-gray-600">
                {{ optional($d->paid_at)->format('d M Y H:i') ?? $d->created_at->format('d M Y H:i') }}
              </td>
              <td class="p-3">
                <div class="font-medium text-gray-900">{{ $d->donor_name ?? 'Hamba ALLAH' }}</div>
                <div class="text-xs text-gray-500">by {{ $d->user->name ?? 'Guest' }}</div>
              </td>
              <td class="p-3">
                <div class="text-xs text-gray-600">{{ $d->donor_email ?? '—' }}</div>
                <div class="text-xs text-gray-600">{{ $d->donor_whatsapp ?? '—' }}</div>
              </td>
              <td class="p-3">
                <a href="{{ route('campaign.show', $d->campaign->slug) }}" target="_blank"
                   class="text-blue-700 hover:text-blue-900">{{ $d->campaign->title ?? '—' }}</a>
              </td>
              <td class="p-3 text-right font-semibold text-gray-900">
                Rp {{ number_format((int)$d->amount,0,',','.') }}
              </td>
              <td class="p-3 text-xs text-gray-600">{{ $d->order_id ?? '—' }}</td>
            </tr>
          @empty
            <tr><td class="p-6 text-center text-gray-500" colspan="6">Belum ada donasi pada periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
      {{ $donations->links() }}
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('reportPage', () => ({
    chart: { labels: [], data: [], max: 1, scale: () => 0, format: n => n.toLocaleString('id-ID') },
    init(selRange, labels, data) {
      this.chart.labels = labels || [];
      this.chart.data   = data   || [];
      const maxv = Math.max(...this.chart.data, 1);
      this.chart.max = maxv;
      this.chart.scale = (v) => Math.round((v / this.chart.max) * 140); // 140px max
    }
  }));
});
</script>
@endpush
@endsection
