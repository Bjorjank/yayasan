<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ChatController;
use App\Models\Campaign;

/*
|--------------------------------------------------------------------------
| PUBLIC (Guest)
|--------------------------------------------------------------------------
*/

// Home (listing kampanye)
Route::get('/', [CampaignController::class, 'index'])->name('home');

// Halaman statis guest
Route::view('/about', 'front.about')->name('about');
Route::view('/contact', 'front.contact')->name('contact');
Route::view('/team', 'front.team')->name('team');

// Contact (dummy submit)
Route::post('/contact', function (Request $r) {
    $data = $r->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email',
        'subject' => 'required|string|max:150',
        'message' => 'required|string|max:5000',
    ]);
    // TODO: kirim email / simpan DB
    return back()->with('ok', 'Pesan terkirim. Terima kasih!');
})->name('contact.submit');

// Katalog donasi (guest) — filter/query
Route::get('/donation', function (Request $request) {
    $q     = $request->query('q');
    $status = $request->query('status');
    $sort  = $request->query('sort', 'latest');

    $campaigns = Campaign::query()
        ->when(
            $q,
            fn($query) =>
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
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

// Detail campaign by slug (implicit binding)
Route::get('/campaign/{campaign:slug}', [CampaignController::class, 'show'])
    ->name('campaign.show');

// Halaman hasil donasi (guest-view, hasil redirect dari Midtrans)
Route::view('/donate/success', 'front.donate-success')->name('donate-success');
Route::view('/donate/failed', 'front.donate-failed')->name('donate-failed');

// Webhook Midtrans (public, signature diverifikasi di controller)
Route::post('/webhooks/midtrans', [PaymentWebhookController::class, 'midtrans'])
    ->name('webhooks.midtrans');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED (Breeze defaults + fitur)
|--------------------------------------------------------------------------
*/

// Dashboard (verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

// Profil & fitur lain yang butuh login
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Donasi: buat transaksi (harus login)
    Route::post('/donate/{campaign}', [DonationController::class, 'createTransaction'])
        ->name('donation.create');

    /*
    |--------------------------------------------------------------------------
    | CHAT (must auth)
    |--------------------------------------------------------------------------
    */
    Route::get('/chat/room/{room}',           [ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/room/{room}/send',     [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/dm/{user}',            [ChatController::class, 'dm'])->name('chat.dm');
    Route::get('/chat/users',                 [ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/room/{room}/messages',  [ChatController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/recent',                [ChatController::class, 'recent'])->name('chat.recent');

    // (opsional) Quick test view
    Route::view('/dm-test', 'dm-test')->name('dm.test');
});


/*
|--------------------------------------------------------------------------
| ADMIN (role: superadmin|admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    Route::get('/admin/campaigns',                 [CampaignController::class, 'adminIndex'])->name('admin.campaigns.index');
    Route::post('/admin/campaigns',                [CampaignController::class, 'store'])->name('admin.campaigns.store');
    Route::put('/admin/campaigns/{campaign}',      [CampaignController::class, 'update'])->name('admin.campaigns.update');
    Route::delete('/admin/campaigns/{campaign}',   [CampaignController::class, 'destroy'])->name('admin.campaigns.destroy');
});


/*
|--------------------------------------------------------------------------
| AUTH routes (Breeze/Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
