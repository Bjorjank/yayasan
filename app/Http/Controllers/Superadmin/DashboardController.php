<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil metrik ringkas
        $totalCampaigns   = Campaign::count();
        $published        = Campaign::where('status', 'published')->count();
        $closed           = Campaign::where('status', 'closed')->count();
        $totalDonations   = Donation::count();
        $sumDonations     = (int) Donation::where('status', 'settlement')->sum('amount');
        $usersTotal       = User::count();
        $latestCampaigns  = Campaign::latest()->take(6)->get();

        // Dummy mini-series 7 hari (ganti dengan data asli kalau siap)
        $daily = collect(range(1,7))->map(fn($d) => [
            'label' => now()->subDays(7-$d)->format('d M'),
            'value' => (int) (rand(4,15) * 1000000),
        ]);

        return view('superadmin.dashboard', compact(
            'totalCampaigns','published','closed','totalDonations',
            'sumDonations','usersTotal','latestCampaigns','daily'
        ));
    }
}
