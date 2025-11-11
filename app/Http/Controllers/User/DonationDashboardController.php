<?php
// app/Http/Controllers/User/DonationDashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\DonationSummaryService;
use Illuminate\Http\Request;

class DonationDashboardController extends Controller
{
    public function __construct(protected DonationSummaryService $summary) {}

    public function index(Request $request)
    {
        $user = $request->user();

        // Ringkasan & Chart
        $totals      = $this->summary->totals($user->id);
        $chartSeries = $this->summary->lastDaysSeries($user->id, 30);
        $composition = $this->summary->topCampaignComposition($user->id, 6);

        // Riwayat: hanya settled, urutkan dari pembayaran terbaru (paid_at desc)
        $search = trim((string) $request->query('q', ''));
        $donations = Donation::query()
            ->with(['campaign:id,title'])
            ->where('user_id', $user->id)
            ->where('status', 'settlement')
            ->whereNotNull('paid_at')
            ->when($search !== '', function ($q) use ($search) {
                $q->whereHas('campaign', function ($qq) use ($search) {
                    $qq->where('title', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('order_id', 'like', "%{$search}%");
            })
            ->orderByDesc('paid_at')
            ->paginate(10)
            ->withQueryString();

        return view('user.donations.dashboard', [
            'totals'      => $totals,
            'chartSeries' => $chartSeries,
            'composition' => $composition,
            'donations'   => $donations,
            'search'      => $search,
        ]);
    }
}
