<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Metrik ringkas
        $totalCampaigns = Campaign::count();
        $published      = Campaign::where('status', 'published')->count();
        $closed         = Campaign::where('status', 'closed')->count();

        $sumDonations   = (int) Donation::where('status','settlement')->sum('amount');
        $totalDonations = (int) Donation::where('status','settlement')->count();

        $usersTotal     = (int) User::count(); // hanya angka; tak ada CRUD Users di UI admin

        return view('admin.dashboard', compact(
            'totalCampaigns','published','closed',
            'sumDonations','totalDonations','usersTotal'
        ));
    }
}
