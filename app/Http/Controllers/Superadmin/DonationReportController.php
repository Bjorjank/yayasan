<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DonationReportController extends Controller
{
    public function index(Request $request)
    {
        // Param filter
        $range        = $request->string('range')->toString() ?: 'today';
        $campaignId   = $request->integer('campaign_id') ?: null;

        // Tentukan rentang waktu utama
        [$start, $end] = $this->resolveRange($range);

        // Query dasar settlement + eager campaign (bawa slug untuk link)
        $base = Donation::with(['campaign:id,title,slug', 'user:id,name,email'])
            ->where('status', 'settlement');

        if ($start && $end) {
            $base->whereBetween('paid_at', [$start, $end]);
        }
        if ($campaignId) {
            $base->where('campaign_id', $campaignId);
        }

        // Clone untuk agregat
        $forAgg = (clone $base);

        // Data tabel (paginate)
        $donations = $base->latest('paid_at')->paginate(15)->withQueryString();

        // Ringkasan agregat
        $totalAmount  = (int) $forAgg->sum('amount');
        $totalCount   = (int) $forAgg->count();

        // Grafik (mengikuti filter campaign bila ada)
        [$chartLabels, $chartData] = $this->buildDailySeries($range, $start, $end, $campaignId);

        // Angka cepat
        $quick = $this->quickNumbers($campaignId);

        // Top 3 campaign untuk SETIAP range (tidak mengikuti filter campaign — selalu global by range)
        $topPerRange = [
            'today' => $this->topCampaignsForRange('today', 3),
            '3d'    => $this->topCampaignsForRange('3d', 3),
            'week'  => $this->topCampaignsForRange('week', 3),
            'month' => $this->topCampaignsForRange('month', 3),
            'all'   => $this->topCampaignsForRange('all', 3),
        ];

        // Opsi select campaign (published saja agar rapi)
        $campaignOptions = Campaign::query()
            ->select('id','title')
            ->orderBy('title')
            ->get();

        $selectedCampaign = $campaignId ? Campaign::find($campaignId) : null;

        return view('superadmin.reports.donations', compact(
            'range', 'start', 'end',
            'donations', 'totalAmount', 'totalCount',
            'chartLabels', 'chartData', 'quick',
            'campaignOptions', 'selectedCampaign', 'campaignId',
            'topPerRange'
        ));
    }

    private function resolveRange(string $range): array
    {
        $now = now();
        return match ($range) {
            'today' => [ $now->copy()->startOfDay(), $now->copy()->endOfDay() ],
            '3d'    => [ $now->copy()->subDays(2)->startOfDay(), $now->copy()->endOfDay() ],
            'week'  => [ $now->copy()->startOfWeek(), $now->copy()->endOfDay() ],
            'month' => [ $now->copy()->startOfMonth(), $now->copy()->endOfDay() ],
            'all'   => [ null, null ],
            default => [ $now->copy()->startOfDay(), $now->copy()->endOfDay() ],
        };
    }

    /**
     * Build series harian mengikuti filter campaign (jika ada).
     */
    private function buildDailySeries(string $range, ?Carbon $start, ?Carbon $end, ?int $campaignId = null): array
    {
        if ($range === 'all' || !$start || !$end) {
            $end   = now()->endOfDay();
            $start = now()->subDays(29)->startOfDay();
        }

        $period = CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->endOfDay());

        $q = Donation::where('status','settlement')
            ->whereBetween('paid_at', [$start, $end]);

        if ($campaignId) {
            $q->where('campaign_id', $campaignId);
        }

        $rows = $q->selectRaw("DATE(paid_at) as d, SUM(amount) as total")
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('total','d'); // ['YYYY-MM-DD' => total]

        $labels = [];
        $data   = [];
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[]   = (int) ($rows[$key] ?? 0);
        }
        return [$labels, $data];
    }

    /**
     * Hitung cepat untuk berbagai range, dapat difilter campaign (opsional).
     */
    private function quickNumbers(?int $campaignId = null): array
    {
        $now = now();
        $defs = [
            'today' => [$now->copy()->startOfDay(),   $now->copy()->endOfDay()],
            '3d'    => [$now->copy()->subDays(2)->startOfDay(), $now->copy()->endOfDay()],
            'week'  => [$now->copy()->startOfWeek(),  $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'all'   => [null, null],
        ];
        $out = [];
        foreach ($defs as $key => [$s, $e]) {
            $q = Donation::where('status','settlement');
            if ($campaignId) $q->where('campaign_id', $campaignId);
            if ($s && $e)   $q->whereBetween('paid_at', [$s, $e]);
            $out[$key] = [
                'amount' => (int) $q->sum('amount'),
                'count'  => (int) $q->count(),
            ];
        }
        return $out;
    }

    /**
     * Top N campaign untuk range tertentu (global — tidak mengikuti filter campaign_id).
     */
    private function topCampaignsForRange(string $range, int $limit = 3)
    {
        [$start, $end] = $this->resolveRange($range);

        $q = Donation::where('status','settlement');
        if ($start && $end) {
            $q->whereBetween('paid_at', [$start, $end]);
        }

        return $q->selectRaw('campaign_id, SUM(amount) as total, COUNT(*) as cnt')
            ->groupBy('campaign_id')
            ->with('campaign:id,title,slug')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }
}
