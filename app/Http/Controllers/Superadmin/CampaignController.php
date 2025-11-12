<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $items = Campaign::latest()->paginate(15)->withQueryString();
        return view('superadmin.campaigns.index', compact('items'));
    }

    public function create()
    {
        return view('superadmin.campaigns.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'        => 'required|string|max:160',
            'slug'         => 'nullable|string|max:190|unique:campaigns,slug',
            'goal_amount'  => 'required|integer|min:0',
            'deadline_at'  => 'nullable|date',
            'status'       => 'required|in:draft,published,closed',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'cover'        => 'nullable|image|max:5120',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['target_amount'] = (int) ($data['goal_amount'] ?? 0);
        unset($data['goal_amount']);

        $data['owner_id'] = auth()->id();

        if ($r->hasFile('cover')) {
            $path = $r->file('cover')->store('campaigns', 'public');
            $data['cover_url'] = $path;
        }

        Campaign::create($data);

        return redirect()->route('superadmin.campaigns.index')->with('ok', 'Campaign dibuat.');
    }

    public function edit(Campaign $campaign)
    {
        return view('superadmin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $r, Campaign $campaign)
    {
        $data = $r->validate([
            'title'        => 'required|string|max:160',
            'slug'         => "nullable|string|max:190|unique:campaigns,slug,{$campaign->id}",
            'goal_amount'  => 'required|integer|min:0',
            'deadline_at'  => 'nullable|date',
            'status'       => 'required|in:draft,published,closed',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'cover'        => 'nullable|image|max:5120',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['target_amount'] = (int) ($data['goal_amount'] ?? 0);
        unset($data['goal_amount']);

        if ($r->hasFile('cover')) {
            if ($campaign->cover_url) {
                Storage::disk('public')->delete($campaign->cover_url);
            }
            $path = $r->file('cover')->store('campaigns', 'public');
            $data['cover_url'] = $path;
        }

        $campaign->update($data);

        return redirect()->route('superadmin.campaigns.index')->with('ok', 'Campaign diupdate.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->cover_url) {
            Storage::disk('public')->delete($campaign->cover_url);
        }
        $campaign->delete();

        return back()->with('ok', 'Campaign dihapus.');
    }
}
