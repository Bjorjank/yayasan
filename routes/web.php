<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\Superadmin\DashboardController as SADashboard;
use App\Http\Controllers\Superadmin\DonationReportController as SADonationReport;
use App\Http\Controllers\Superadmin\UserController as SAUser;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Http\Controllers\User\DonationDashboardController;

use App\Models\Campaign;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home (tetap pakai controller punyamu)
Route::get('/', [CampaignController::class, 'index'])->name('home');

// Halaman statis (guest)
Route::view('/', 'welcome')->name('home');
Route::view('/about', 'front.about')->name('about');
Route::view('/contact', 'front.contact')->name('contact');
Route::view('/team', 'front.team')->name('team');
Route::view('/privacy', 'front.privacy')->name('privacy');
Route::view('/terms', 'front.terms')->name('terms');
Route::view('/faq', 'front.faq')->name('faq');

// Contact submit
Route::post('/contact', function (Request $r) {
    $r->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email',
        'subject' => 'required|string|max:150',
        'message' => 'required|string|max:5000',
    ]);
    // TODO: kirim email / simpan DB
    return back()->with('ok', 'Pesan terkirim. Terima kasih!');
})->name('contact.submit');

// Katalog donasi (guest) — filter/query/sort (disatukan)
Route::get('/donation', function (Request $request) {
    $q      = $request->query('q');
    $status = $request->query('status');
    $sort   = $request->query('sort', 'latest');

    $campaigns = Campaign::query()
        ->when($q, fn($query) =>
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('excerpt', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
                // NOTE: jika kolom "category" ada, bisa tambahkan orWhere('category', 'like', "%{$q}%")
            })
        )
        ->when($status, fn($query) => $query->where('status', $status))
        ->when($sort === 'target_desc', fn($q) => $q->orderByDesc('target_amount'))
        ->when($sort === 'target_asc',  fn($q) => $q->orderBy('target_amount'))
        ->when($sort === 'latest',      fn($q) => $q->latest())
        ->paginate(9)
        ->withQueryString();

    return view('donation.index', compact('campaigns', 'q', 'status', 'sort'));
})->name('donation');

// Detail campaign (slug)
Route::get('/campaign/{campaign:slug}', [CampaignController::class, 'show'])->name('campaign.show');

// Donasi (buat transaksi) — login opsional (tetap satu definisi saja)
Route::post('/donate/{campaign}', [DonationController::class, 'createTransaction'])
    ->name('donation.create');

// Halaman hasil donasi (pakai penamaan punyamu)
Route::view('/donate/success', 'front.donate-success')->name('donate.success');
Route::view('/donate/failed',  'front.donate-failed')->name('donate.failed');

// Webhook Midtrans (public)
Route::post('/webhooks/midtrans', [PaymentWebhookController::class, 'midtrans'])
    ->name('webhooks.midtrans');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Breeze defaults + fitur)
|--------------------------------------------------------------------------
*/

// Dashboard utama (verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

// Profil & Chat
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // CHAT
    Route::get('/chat/room/{room}',          [ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/room/{room}/send',    [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/dm/{user}',           [ChatController::class, 'dm'])->name('chat.dm');
    Route::get('/chat/users',                [ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/room/{room}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/recent',               [ChatController::class, 'recent'])->name('chat.recent');
    Route::view('/dm-test', 'dm-test')->name('dm.test');
});

/*
|--------------------------------------------------------------------------
| SUPERADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SADashboard::class, 'index'])->name('dashboard');

        // Campaigns (index)
        Route::get('/campaigns', function () {
            $items = Campaign::latest()->paginate(15)->withQueryString();
            return view('superadmin.campaigns.index', compact('items'));
        })->name('campaigns.index');

        // Create (view)
        Route::view('/campaigns/create', 'superadmin.campaigns.create')->name('campaigns.create');

        // Edit (binding)
        Route::get('/campaigns/{campaign}/edit', function (Campaign $campaign) {
            return view('superadmin.campaigns.edit', compact('campaign'));
        })->name('campaigns.edit');

        // Donations (list campaign untuk laporan)
        Route::get('/donations', function () {
            $q    = request('q');
            $sort = request('sort', 'latest');

            $campaigns = Campaign::query()
                ->when($q, fn($query) =>
                    $query->where(function ($w) use ($q) {
                        $w->where('title', 'like', "%{$q}%")
                          ->orWhere('description', 'like', "%{$q}%")
                          ->orWhere('category', 'like', "%{$q}%"); // jika ada kolom category
                    })
                )
                ->when($sort === 'target_desc', fn($q) => $q->orderByDesc('target_amount'))
                ->when($sort === 'target_asc',  fn($q) => $q->orderBy('target_amount'))
                ->when($sort === 'latest',      fn($q) => $q->latest())
                ->paginate(12)
                ->withQueryString();

            return view('superadmin.donation.index', compact('campaigns','q','sort'));
        })->name('donations.index');

        // Laporan Donasi
        Route::get('/reports/donations', [SADonationReport::class, 'index'])->name('reports.donations');

        // Settings (stub)
        Route::view('/settings', 'superadmin.stub')->name('settings');

        // ===== SUPERADMIN USERS (dikembalikan) =====
        Route::get('/users',           [SAUser::class, 'index'])->name('users.index');
        Route::get('/users/{user}',    [SAUser::class, 'show'])->name('users.show');
        Route::post('/users',          [SAUser::class, 'store'])->name('users.store');
        Route::put('/users/{user}',    [SAUser::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [SAUser::class, 'destroy'])->name('users.destroy');
    });

/*
|--------------------------------------------------------------------------
| ADMIN AREA (role: superadmin|admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:superadmin|admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Laporan donasi
        Route::get('/reports/donations', [\App\Http\Controllers\Admin\DonationReportController::class, 'index'])
            ->name('reports.donations');
        Route::get('/reports/donations/campaign/{campaign}', [\App\Http\Controllers\Admin\DonationReportController::class, 'byCampaign'])
            ->name('reports.donations.campaign');

        // Users (khusus admin)
        Route::get('/users',           [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',    [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users',          [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',    [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

/*
|--------------------------------------------------------------------------
| ADMIN CAMPAIGN CRUD (endpoint /admin/campaigns)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    Route::get('/admin/campaigns',               [CampaignController::class, 'adminIndex'])->name('admin.campaigns.index');
    Route::post('/admin/campaigns',              [CampaignController::class, 'store'])->name('admin.campaigns.store');
    Route::put('/admin/campaigns/{campaign}',    [CampaignController::class, 'update'])->name('admin.campaigns.update');
    Route::delete('/admin/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('admin.campaigns.destroy');
});

/*
|--------------------------------------------------------------------------
| USER DASHBOARD GROUP
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Dashboard utama user
        Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

        // Dashboard donasi user
        Route::get('/dashboard/donations', [DonationDashboardController::class, 'index'])
            ->name('donations.dashboard');
    });

/*
|--------------------------------------------------------------------------
| DONATION FLOW (checkout/confirm/cancel/expire)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/donate/{donation}/checkout', [DonationController::class, 'checkout'])->name('donation.checkout');
    Route::post('/donate/{donation}/confirm', [DonationController::class, 'confirm'])->name('donation.confirm');
    Route::post('/donate/{donation}/cancel',  [DonationController::class, 'cancel'])->name('donation.cancel');
    Route::post('/donate/{donation}/expire',  [DonationController::class, 'expire'])->name('donation.expire');
});

/*
|--------------------------------------------------------------------------
| AUTH scaffolding
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
