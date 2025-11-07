<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
        'meta'    => 'array',
    ];

    // Status constants
    public const STATUS_PENDING    = 'pending';
    public const STATUS_SETTLEMENT = 'settlement';
    public const STATUS_EXPIRE     = 'expire';
    public const STATUS_REFUND     = 'refund';

    /* ---------- Relations ---------- */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ---------- Scopes ---------- */
    public function scopeSettled($q)
    {
        return $q->where('status', self::STATUS_SETTLEMENT);
    }
}
