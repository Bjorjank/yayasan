<?php
// app/Http/Controllers/DonationController.php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Payments\Contracts\PaymentGateway;

class DonationController extends Controller
{
    public function createTransaction(Request $r, Campaign $campaign, PaymentGateway $gateway)
    {
        abort_if($campaign->status !== 'published', 404);

        $data = $r->validate([
            'donor_name'     => 'nullable|string|max:120',       // nullable → default Hamba ALLAH
            'donor_email'    => 'nullable|email|max:160',
            'donor_whatsapp' => 'nullable|string|max:32',
            'amount'         => 'required|integer|min:1000',
        ]);

        // Default nama donor
        $donorName = trim((string) ($data['donor_name'] ?? ''));
        if ($donorName === '') {
            $donorName = 'Hamba ALLAH';
        }

        // Generate ORDER ID yang rapi & unik (wajib sebelum dipakai)
        $orderId = sprintf(
            'DON-%d-%s-%s',
            $campaign->id,
            now()->format('YmdHis'),
            Str::upper(Str::random(5))
        );

        // Simpan donasi (status awal pending)
        $donation = Donation::create([
            'campaign_id'    => $campaign->id,
            'user_id'        => auth()->id(),
            'donor_name'     => $donorName,
            'donor_email'    => $data['donor_email'] ?? null,
            'donor_whatsapp' => $data['donor_whatsapp'] ?? null,
            'amount'         => (int) $data['amount'],
            'status'         => 'pending',
            'order_id'       => $orderId,
        ]);

        // Buat “transaksi” via gateway aktif (fake gateway kita)
        $info = $gateway->create($donation);

        return redirect()->route('donation.checkout', $donation)
            ->with('ok', 'Donasi dibuat. Ikuti instruksi pembayaran.')
            ->with('payinfo', $info);
    }

    public function checkout(Donation $donation)
    {
        $campaign = $donation->campaign;
        return view('donation.checkout', compact('donation','campaign'));
    }

    public function confirm(Request $r, Donation $donation, PaymentGateway $gateway)
    {
        $gateway->settle($donation);
        return redirect()->route('donation.checkout', $donation)
            ->with('ok', 'Pembayaran dikonfirmasi. Terima kasih!');
    }

    public function cancel(Donation $donation, PaymentGateway $gateway)
    {
        $gateway->cancel($donation);
        return redirect()->route('donation.checkout', $donation)
            ->with('ok', 'Transaksi dibatalkan.');
    }

    public function expire(Donation $donation, PaymentGateway $gateway)
    {
        $minutes = (int) (config('payments.drivers.fake.expire_minutes') ?? 60);
        if ($donation->status === 'pending' && $donation->created_at->addMinutes($minutes)->isPast()) {
            $gateway->expire($donation);
        }
        return back()->with('ok', 'Transaksi diperiksa untuk kedaluwarsa.');
    }
}
