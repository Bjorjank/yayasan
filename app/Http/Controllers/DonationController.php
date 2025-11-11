<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Throwable;

// Midtrans opsional (jika sudah di-install & env diset)
use Midtrans\Config as MidConfig;
use Midtrans\CoreApi;

class DonationController extends Controller
{
    public function createTransaction(Request $r, Campaign $campaign)
    {
        abort_if($campaign->status !== 'published', 404);

        $data = $r->validate([
            'donor_name'  => 'required|string|max:120',
            'donor_email' => 'nullable|email|max:160',
            'amount'      => 'required|integer|min:1000',
        ]);

        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id'     => auth()->id(),
            'donor_name'  => $data['donor_name'],
            'donor_email' => $data['donor_email'] ?? null,
            'amount'      => $data['amount'],
            'status'      => 'pending',
        ]);

        // Jika MIDTRANS belum dikonfigurasi, fallback ke simulasi
        $serverKey = (string) config('midtrans.server_key', '');
        $useGateway = strlen($serverKey) > 10;

        if (!$useGateway) {
            // SIMULASI: tandai settlement langsung supaya progress terlihat
            $donation->update([
                'status'         => 'settlement',
                'payment_ref'    => 'SIM-'.$donation->id,
                'payment_method' => 'simulation',
                'paid_at'        => now(),
                'payload'        => ['simulated' => true],
            ]);

            return redirect()->route('campaign.show', $campaign)
                ->with('ok', 'Donasi tersimpan (simulasi). MIDTRANS belum dikonfigurasi.');
        }

        // === MIDTRANS AKTIF ===
        try {
            MidConfig::$serverKey     = config('midtrans.server_key');
            MidConfig::$isProduction  = (bool) config('midtrans.is_production', false);
            MidConfig::$isSanitized   = true;
            MidConfig::$is3ds         = true;

            $orderId = 'DON-'.$donation->id.'-'.now()->format('YmdHis');

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $donation->amount,
                ],
                'customer_details' => [
                    'first_name' => $donation->donor_name,
                    'email'      => $donation->donor_email,
                ],
                'item_details' => [[
                    'id'       => 'campaign-'.$campaign->id,
                    'price'    => (int) $donation->amount,
                    'quantity' => 1,
                    'name'     => 'Donasi: '.$campaign->title,
                ]],
                'callbacks' => [
                    'finish' => route('campaign.show', $campaign),
                ],
            ];

            // contoh: VA BCA; silakan ganti sesuai metode
            $charge = CoreApi::charge(array_merge($params, [
                'payment_type'  => 'bank_transfer',
                'bank_transfer' => ['bank' => 'bca'],
            ]));

            $donation->update([
                'payment_ref'    => $orderId,
                'payment_method' => $charge['payment_type'] ?? 'bank_transfer',
                'payload'        => $charge,
            ]);

            return redirect()->route('campaign.show', $campaign)
                ->with('ok', 'Donasi dibuat. Selesaikan pembayaran sesuai instruksi.')
                ->with('payinfo', $charge);

        } catch (Throwable $e) {
            // fallback aman—jangan fatal ketika sandbox error
            $donation->update([
                'status'  => 'pending',
                'payload' => ['error' => true, 'message' => $e->getMessage()],
            ]);

            return redirect()->route('campaign.show', $campaign)
                ->with('ok', 'Gagal membuat transaksi Midtrans: '.$e->getMessage());
        }
    }
}
