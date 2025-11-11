<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'amount',
        'order_id',        // ⬅️ tambahkan
        'donor_whatsapp',   // ⬅️ baru
        'status',          // pending|settlement|expire|cancel
        'payment_ref',     // order_id / transaction id
        'payment_method',  // gopay/va/cc/...
        'paid_at',
        'payload',         // json (response gateway)
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    public function campaign(){ return $this->belongsTo(Campaign::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
