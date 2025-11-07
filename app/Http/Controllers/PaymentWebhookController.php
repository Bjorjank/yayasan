<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/PaymentWebhookController.php
use App\Models\Donation;
use App\Models\WebhookEvent;

class PaymentWebhookController extends Controller {
    public function midtrans(Request $req) {
        // Simpan payload mentah
        $event = WebhookEvent::create([
            'provider'=>'midtrans',
            'payload_json'=>$req->all(),
        ]);

        // Verifikasi signature (opsional untuk Snap status); jika pakai Core API, WAJIB
        // $signatureKey = $req->header('X-Signature')... (implementasi sesuai dok Midtrans)

        $orderId = $req->input('order_id');
        $status  = $req->input('transaction_status'); // capture|settlement|pending|deny|expire|cancel|refund

        $don = Donation::where('order_id',$orderId)->first();
        if(!$don) return response()->json(['ok'=>true]); // abaikan jika tidak ada

        if(in_array($status,['settlement','capture'])) {
            $don->update(['status'=>'settlement','paid_at'=>now(),'external_ref'=>$req->input('transaction_id')]);
        } elseif($status==='expire') {
            $don->update(['status'=>'expire']);
        } elseif($status==='refund') {
            $don->update(['status'=>'refund']);
        } else {
            $don->update(['status'=>$status]);
        }

        $event->update(['status'=>'processed','processed_at'=>now()]);
        return response()->json(['ok'=>true]);
    }
}
