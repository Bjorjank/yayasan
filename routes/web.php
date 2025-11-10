<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ChatController;
use App\Models\Campaign;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [CampaignController::class, 'index'])->name('home');

// Halaman donasi (list campaign)
Route::get('/donation', function (\Illuminate\Http\Request $request) {
    $q = $request->query('q');
    $status = $request->query('status');
    $sort = $request->query('sort', 'latest');

    $campaigns = Campaign::query()
        ->when($q, fn($query) =>
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
        )
        ->when($status, fn($query) => $query->where('status', $status))
        ->when($sort === 'target_desc', fn($q) => $q->orderByDesc('target_amount'))
        ->when($sort === 'target_asc', fn($q) => $q->orderBy('target_amount'))
        ->when($sort === 'latest', fn($q) => $q->latest())
        ->paginate(9)
        ->withQueryString();

    return view('donation.index', compact('campaigns', 'q', 'status', 'sort'));
})->name('donation');

// Static pages
Route::view('/about', 'front.about')->name('about');
Route::view('/contact', 'front.contact')->name('contact');
Route::view('/faq', 'front.faq')->name('faq');
Route::view('/privacy', 'front.privacy')->name('privacy');
Route::view('/terms', 'front.terms')->name('terms');
Route::view('/team', 'front.team')->name('team');

// Contact form submission (optional)
Route::post('/contact', function (\Illuminate\Http\Request $r) {
    $data = $r->validate([
        'name' => 'required',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required'
    ]);
    // TODO: kirim email / simpan DB
    return back()->with('ok', 'Pesan terkirim. Terima kasih!');
})->name('contact.submit');

// Campaign detail (slug binding)
Route::get('/campaign/{campaign:slug}', [CampaignController::class, 'show'])->name('campaign.show');

// Donasi (login opsional)
Route::post('/donate/{campaign}', [DonationController::class, 'createTransaction'])
    ->name('donation.create');

// Halaman hasil donasi
Route::view('/donate/success', 'front.donate-success')->name('donate.success');
Route::view('/donate/failed', 'front.donate-failed')->name('donate.failed');

// Webhook pembayaran Midtrans (public)
Route::post('/webhooks/midtrans', [PaymentWebhookController::class, 'midtrans'])
    ->name('webhooks.midtrans');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | CHAT
    |--------------------------------------------------------------------------
    */
    Route::get('/chat/room/{room}', [ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/room/{room}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/dm/{user}', [ChatController::class, 'dm'])->name('chat.dm');
    Route::get('/chat/users', [ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/room/{room}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/recent', [ChatController::class, 'recent'])->name('chat.recent');
    Route::view('/dm-test', 'dm-test')->name('dm.test');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (role: superadmin|admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    Route::get('/admin/campaigns', [CampaignController::class, 'adminIndex'])->name('admin.campaigns.index');
    Route::post('/admin/campaigns', [CampaignController::class, 'store'])->name('admin.campaigns.store');
    Route::put('/admin/campaigns/{campaign}', [CampaignController::class, 'update'])->name('admin.campaigns.update');
    Route::delete('/admin/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('admin.campaigns.destroy');
});

require __DIR__ . '/auth.php';
