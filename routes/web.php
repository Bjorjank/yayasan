<?php

use Illuminate\Support\Facades\Route;
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



Route::middleware(['auth','role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SADashboard::class, 'index'])->name('dashboard');

        // Campaigns (index kirim $items)
        Route::get('/campaigns', function () {
            $items = Campaign::latest()->paginate(15)->withQueryString();
            return view('superadmin.campaigns.index', compact('items'));
        })->name('campaigns.index');

        // Create (view statis OK)
        Route::view('/campaigns/create', 'superadmin.campaigns.create')->name('campaigns.create');

        // Edit (pakai binding agar $campaign tersedia di view)
        Route::get('/campaigns/{campaign}/edit', function (Campaign $campaign) {
            return view('superadmin.campaigns.edit', compact('campaign'));
        })->name('campaigns.edit');

        // Donations (kirim $campaigns + query)
        Route::get('/donations', function () {
            $q    = request('q');
            $sort = request('sort', 'latest');

            $campaigns = Campaign::query()
                ->when($q, fn($query) =>
                    $query->where(function ($w) use ($q) {
                        $w->where('title', 'like', "%{$q}%")
                          ->orWhere('description', 'like', "%{$q}%")
                          ->orWhere('category', 'like', "%{$q}%");
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
        Route::get('/reports/donations', [SADonationReport::class, 'index'])
            ->name('reports.donations');
        // Lainnya (stub)
        Route::view('/settings', 'superadmin.stub')->name('settings');


    });
    

    

Route::middleware(['auth','role:superadmin|admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Laporan donasi (mirip superadmin)
        Route::get('/reports/donations', [\App\Http\Controllers\Admin\DonationReportController::class, 'index'])
            ->name('reports.donations');

        // (Opsional) filter by campaign tertentu
        Route::get('/reports/donations/campaign/{campaign}', [\App\Http\Controllers\Admin\DonationReportController::class, 'byCampaign'])
            ->name('reports.donations.campaign');
        
        // users
        // users
        Route::get('/users',               [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',        [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users',              [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',        [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',     [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

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



// buat donasi (sudah ada dari sebelumnya):
// Route::post('/donate/{campaign}', [DonationController::class,'createTransaction'])->name('donation.create');

// halaman instruksi pembayaran
Route::get('/donate/{donation}/checkout', [DonationController::class, 'checkout'])
    ->name('donation.checkout')
    ->middleware('auth'); // opsional: kalau mau hanya user login yang bisa lihat

// tombol "Saya sudah bayar" (settlement)
Route::post('/donate/{donation}/confirm', [DonationController::class, 'confirm'])
    ->name('donation.confirm')
    ->middleware('auth'); // opsional

// tombol "Batalkan"
Route::post('/donate/{donation}/cancel', [DonationController::class, 'cancel'])
    ->name('donation.cancel')
    ->middleware('auth'); // opsional

// endpoint untuk menandai expire (bisa dipanggil cron/command)
Route::post('/donate/{donation}/expire', [DonationController::class, 'expire'])
    ->name('donation.expire')
    ->middleware('auth'); // opsional



require __DIR__ . '/auth.php';
