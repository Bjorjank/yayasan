<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DonationReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->string('range')->toString() ?: 'today';
        [$start, $end] = $this->resolveRange($range);

        $base = Donation::with(['campaign:id,title,slug', 'user:id,name,email'])
            ->where('status', 'settlement');

        if ($start && $end) {
            $base->whereBetween('paid_at', [$start, $end]);
        }

        $forAgg = (clone $base);

        $donations   = $base->latest('paid_at')->paginate(15)->withQueryString();
        $totalAmount = (int) $forAgg->sum('amount');
        $totalCount  = (int) $forAgg->count();

        // Top campaigns (periode & filter aktif saat ini)
        $topCampaigns = $this->buildTopCampaigns((clone $forAgg));

        [$chartLabels, $chartData] = $this->buildDailySeries($range, $start, $end);
        $quick = $this->quickNumbers();

        // untuk dropdown campaign filter cepat
        $campaignOptions = Campaign::select('id','title','slug')->orderBy('title')->get();

        return view('admin.reports.donations', compact(
            'donations','range','start','end',
            'totalAmount','totalCount','topCampaigns',
            'chartLabels','chartData','quick','campaignOptions'
        ));
    }

    public function byCampaign(Request $request, Campaign $campaign)
    {
        $range = $request->string('range')->toString() ?: 'today';
        [$start, $end] = $this->resolveRange($range);

        $base = Donation::with(['campaign:id,title,slug', 'user:id,name,email'])
            ->where('status', 'settlement')
            ->where('campaign_id', $campaign->id);

        if ($start && $end) {
            $base->whereBetween('paid_at', [$start, $end]);
        }

        $forAgg = (clone $base);

        $donations   = $base->latest('paid_at')->paginate(15)->withQueryString();
        $totalAmount = (int) $forAgg->sum('amount');
        $totalCount  = (int) $forAgg->count();

        // Top campaigns khusus campaign yang dipilih (akan berisi 1 item)
        $topCampaigns = $this->buildTopCampaigns((clone $forAgg));

        [$chartLabels, $chartData] = $this->buildDailySeries($range, $start, $end, $campaign->id);
        $quick = $this->quickNumbers($campaign->id);

        $campaignOptions = Campaign::select('id','title','slug')->orderBy('title')->get();

        return view('admin.reports.donations', compact(
            'donations','range','start','end',
            'totalAmount','totalCount','topCampaigns',
            'chartLabels','chartData','quick','campaignOptions','campaign'
        ));
    }

    /**
     * Kembalikan daftar top campaign (total nominal & count) berdasarkan query agregat yang diberikan.
     * Expectation: $forAgg sudah memuat filter waktu/status/campaign (kalau ada).
     */
    private function buildTopCampaigns($forAgg, int $limit = 5)
    {
        return $forAgg
            ->selectRaw('campaign_id, SUM(amount) as total, COUNT(*) as cnt')
            ->groupBy('campaign_id')
            ->with('campaign:id,title,slug')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
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

    private function buildDailySeries(string $range, ?Carbon $start, ?Carbon $end, ?int $campaignId = null): array
    {
        if ($range === 'all' || !$start || !$end) {
            $end   = now()->endOfDay();
            $start = now()->subDays(29)->startOfDay();
        }

        $period = CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->endOfDay());

        $rowsQ = Donation::where('status','settlement')
            ->whereBetween('paid_at', [$start, $end]);

        if ($campaignId) {
            $rowsQ->where('campaign_id', $campaignId);
        }

        $rows = $rowsQ
            ->selectRaw("DATE(paid_at) as d, SUM(amount) as total")
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('total', 'd');

        $labels = [];
        $data   = [];
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[]   = (int) ($rows[$key] ?? 0);
        }

        return [$labels, $data];
    }

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
            if ($s && $e)    $q->whereBetween('paid_at', [$s, $e]);

            $out[$key] = [
                'amount' => (int) $q->sum('amount'),
                'count'  => (int) $q->count(),
            ];
        }

        return $out;
    }
}
