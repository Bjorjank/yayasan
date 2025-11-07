<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'deadline_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CLOSED    = 'closed';

    /* ---------- Relations ---------- */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }

    /* ---------- Scopes ---------- */
    public function scopePublished($q)
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }

    public function getCollectedAmountAttribute(): int
    {
        return (int) $this->donations()
            ->where('status', Donation::STATUS_SETTLEMENT)
            ->sum('amount');
    }

    public function getProgressPercentAttribute(): float
    {
        $target = max(1, (int) $this->target_amount);
        return min(100, round(($this->collected_amount / $target) * 100, 2));
    }
}
