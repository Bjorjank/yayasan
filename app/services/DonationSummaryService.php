<?php
// app/Services/DonationSummaryService.php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class DonationSummaryService
{
    /** Hanya donasi settled (sukses) milik user, wajib paid_at terisi */
    protected function settled(int $userId): Builder
    {
        return Donation::query()
            ->where('user_id', $userId)
            ->where('status', 'settlement')
            ->whereNotNull('paid_at');
    }

    /** Ringkasan total: harian, 3 hari, mingguan, bulanan, all-time (berdasarkan paid_at) */
    public function totals(int $userId): array
    {
        $now = now();

        $dailyStart    = $now->copy()->startOfDay();
        $threeDayStart = $now->copy()->subDays(2)->startOfDay(); // hari ini + 2 hari sebelumnya
        $weeklyStart   = $now->copy()->startOfWeek();
        $monthlyStart  = $now->copy()->startOfMonth();

        $sumBetween = function (Carbon $from, ?Carbon $to = null) use ($userId, $now) {
            return (clone $this->settled($userId))
                ->whereBetween('paid_at', [$from, $to ?: $now])
                ->sum('amount');
        };

        return [
            'daily'     => (int) $sumBetween($dailyStart),
            'threeDay'  => (int) $sumBetween($threeDayStart),
            'weekly'    => (int) $sumBetween($weeklyStart),
            'monthly'   => (int) $sumBetween($monthlyStart),
            'allTime'   => (int) (clone $this->settled($userId))->sum('amount'),
        ];
    }

    /** Series 30 hari terakhir untuk chart line (label per tanggal, total jumlah per hari) */
    public function lastDaysSeries(int $userId, int $days = 30): Collection
    {
        $start = now()->copy()->subDays($days - 1)->startOfDay();

        // Ambil dan grup per tanggal (paid_at)
        $base = $this->settled($userId)
            ->where('paid_at', '>=', $start)
            ->get(['amount', 'paid_at'])
            ->groupBy(fn ($d) => $d->paid_at->format('Y-m-d'))
            ->map(fn ($rows) => (int) $rows->sum('amount'));

        // Pastikan setiap hari ada (0 jika tidak ada donasi)
        $series = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->copy()->subDays($i)->format('Y-m-d');
            $series->push([
                'date'  => $date,
                'total' => $base[$date] ?? 0,
            ]);
        }
        return $series;
    }

    /** Komposisi per campaign untuk donut chart (Top N) */
    public function topCampaignComposition(int $userId, int $limit = 6): Collection
    {
        return $this->settled($userId)
            ->selectRaw('campaign_id, SUM(amount) as total_amount')
            ->with('campaign:id,title')
            ->groupBy('campaign_id')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'label' => optional($row->campaign)->title ?? 'Tanpa Campaign',
                'value' => (int) $row->total_amount,
            ]);
    }
}
