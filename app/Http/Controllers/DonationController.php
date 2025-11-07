<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/DonationController.php
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\MidtransService;
use Illuminate\Support\Str;


class DonationController extends Controller {
    public function __construct(private MidtransService $mid){}

    public function createTransaction(Request $req, Campaign $campaign) {
        $req->validate(['amount'=>'required|integer|min:1000']);
        $orderId = 'DON-' . Str::ulid();

        $donation = Donation::create([
            'campaign_id'=>$campaign->id,
            'user_id'=>auth()->id(),
            'amount'=>$req->integer('amount'),
            'status'=>'pending',
            'order_id'=>$orderId,
            'payment_provider'=>'midtrans',
            'meta'=>['channel'=>'web'],
        ]);

        $snapParams = [
            'transaction_details'=>[
                'order_id'=>$orderId,
                'gross_amount'=>$donation->amount,
            ],
            'customer_details'=>[
                'first_name'=>auth()->user()->name,
                'email'=>auth()->user()->email,
            ],
            // enable QRIS/VA/ewallet otomatis via Snap
        ];

        $snap = $this->mid->createSnap($snapParams);

        return redirect()->away($snap['redirect_url']);
    }
}
