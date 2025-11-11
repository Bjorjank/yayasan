<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class PaymentWebhookController extends Controller
{
    public function midtrans(Request $r)
    {
        $serverKey = (string) config('midtrans.server_key', '');
        if (strlen($serverKey) < 10) {
            // Jika server key kosong, tolak webhook agar tidak sembarangan
            return response()->json(['ok'=>false,'error'=>'GATEWAY_DISABLED'], 403);
        }

        $data = json_decode($r->getContent(), true) ?: [];

        // verifikasi signature
        $order_id    = $data['order_id'] ?? '';
        $status_code = $data['status_code'] ?? '';
        $gross       = $data['gross_amount'] ?? '';
        $signature   = $data['signature_key'] ?? '';
        $expected    = hash('sha512', $order_id.$status_code.$gross.$serverKey);

        if (!hash_equals($expected, $signature)) {
            return response()->json(['ok'=>false,'error'=>'INVALID_SIGNATURE'], 403);
        }

        if (!preg_match('/^DON-(\d+)-/', $order_id, $m)) {
            return response()->json(['ok'=>false,'error'=>'ORDER_ID_INVALID'], 422);
        }

        $donationId = (int) $m[1];
        $don = Donation::find($donationId);
        if (!$don) return response()->json(['ok'=>false,'error'=>'NOT_FOUND'], 404);

        $tx = $data['transaction_status'] ?? '';
        if ($tx === 'capture' || $tx === 'settlement') {
            $don->status   = 'settlement';
            $don->paid_at  = now();
        } elseif ($tx === 'expire') {
            $don->status   = 'expire';
        } elseif ($tx === 'cancel' || $tx === 'deny') {
            $don->status   = 'cancel';
        } else {
            $don->status   = 'pending';
        }

        $don->payment_method = $data['payment_type'] ?? $don->payment_method;
        $don->payload        = $data;
        $don->save();

        return response()->json(['ok'=>true]);
    }
}
