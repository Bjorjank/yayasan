<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ChatController;

// === PUBLIC ===
Route::get('/', [CampaignController::class, 'index'])->name('home');
Route::view('/about', 'front.about')->name('about');
// Guest-facing pages (tanpa controller)
Route::view('/', 'welcome')->name('home');
Route::view('/about', 'front.about')->name('about');
Route::view('/contact', 'front.contact')->name('contact');
Route::view('/faq', 'front.faq')->name('faq');
Route::view('/privacy', 'front.privacy')->name('privacy');
Route::view('/terms', 'front.terms')->name('terms');
Route::view('/team', 'front.team')->name('team');

// (opsional) halaman hasil donasi — tetap guest & dummy
Route::view('/donate/success', 'front.donate-success')->name('donate-success');
Route::view('/donate/failed', 'front.donate-failed')->name('donate-failed');
Route::get('/campaign/{slug}', [CampaignController::class, 'show'])->name('campaign.show');

// Donasi (harus login)
Route::post('/donate/{campaign}', [DonationController::class, 'createTransaction'])
    ->middleware('auth')->name('donation.create');

// Webhook pembayaran (public)
Route::post('/webhooks/midtrans', [PaymentWebhookController::class, 'midtrans'])
    ->name('webhooks.midtrans');

// Dashboard (hanya contoh bawaan Breeze)
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

// Profile (bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === CHAT (must auth) ===
Route::middleware('auth')->group(function () {
    Route::get('/chat/room/{room}', [ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/room/{room}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/dm/{user}', [ChatController::class, 'dm'])->name('chat.dm');

    // NEW: list pengguna untuk DM (JSON)
    Route::get('/chat/users', [ChatController::class, 'users'])->name('chat.users');

    // NEW: riwayat pesan room (JSON)
    Route::get('/chat/room/{room}/messages', [ChatController::class, 'messages'])->name('chat.messages');
});
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::view('/dm-test', 'dm-test')->name('dm.test');
});


Route::middleware('auth')->group(function () {
    // ... existing chat routes
    Route::get('/chat/recent', [ChatController::class, 'recent'])->name('chat.recent');
});


require __DIR__ . '/auth.php';
