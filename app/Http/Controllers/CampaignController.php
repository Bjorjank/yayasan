<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller {
    public function index() {
        $campaigns = Campaign::query()
            ->where('status','published')
            ->latest()->paginate(12);
        return view('campaign.index', compact('campaigns'));
    }
    public function show(string $slug) {
        $c = Campaign::where('slug',$slug)->firstOrFail();
        $sum = $c->donations()->where('status','settlement')->sum('amount');
        return view('campaign.show', compact('c','sum'));
    }
}
