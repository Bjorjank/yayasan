<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    // LIST publik
    public function index(Request $request)
    {
        $campaigns = Campaign::published()
            ->when($request->filled('q'), fn($q) =>
                $q->where('title', 'like', '%'.$request->string('q')->toString().'%')
            )
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('campaign.index', compact('campaigns'));
    }

    // DETAIL publik
    public function show(Campaign $campaign)
    {
        abort_if($campaign->status !== 'published', 404);

        $sum = $campaign->donations()->where('status','settlement')->sum('amount');
        return view('campaign.show', ['c' => $campaign, 'sum' => $sum]);
    }

    // === ADMIN SEDERHANA ===

    public function adminIndex()
    {
        $items = Campaign::latest()->paginate(15);
        return view('admin.campaigns.index', compact('items'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $r)
    {
        // Validasi sesuai SKEMA DB KAMU
        $data = $r->validate([
            'title'        => 'required|string|max:160',
            'slug'         => 'nullable|string|max:190|unique:campaigns,slug',
            // NOTE: 'goal_amount' dari form akan dipetakan ke 'target_amount'
            'goal_amount'  => 'required|integer|min:0',
            'deadline_at'  => 'nullable|date',
            'status'       => 'required|in:draft,published,closed',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            // Upload cover ke cover_url (≤ 5MB)
            'cover'        => 'nullable|image|max:5120',
        ]);

        // slug fallback
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        // Map goal_amount -> target_amount sesuai kolom DB
        $data['target_amount'] = (int) ($data['goal_amount'] ?? 0);
        unset($data['goal_amount']);

        // Pemilik campaign WAJIB (mengatasi error owner_id)
        $data['owner_id'] = auth()->id();

        // Simpan cover (opsional)
        if ($r->hasFile('cover')) {
            $path = $r->file('cover')->store('campaigns', 'public');
            $data['cover_url'] = $path; // simpan RELATIVE storage path
        }

        Campaign::create($data);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('ok','Campaign dibuat.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $r, Campaign $campaign)
    {
        $data = $r->validate([
            'title'        => 'required|string|max:160',
            'slug'         => "nullable|string|max:190|unique:campaigns,slug,{$campaign->id}",
            // tetap terima goal_amount dari form edit jika ada,
            // dan petakan ke target_amount
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

        // Cover baru (opsional): hapus lama lalu simpan baru
        if ($r->hasFile('cover')) {
            if ($campaign->cover_url) {
                Storage::disk('public')->delete($campaign->cover_url);
            }
            $path = $r->file('cover')->store('campaigns', 'public');
            $data['cover_url'] = $path;
        }

        $campaign->update($data);

        return back()->with('ok','Campaign diupdate.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->cover_url) {
            Storage::disk('public')->delete($campaign->cover_url);
        }
        $campaign->delete();

        return back()->with('ok','Campaign dihapus.');
    }
}
