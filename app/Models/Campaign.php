<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = [
        'owner_id',
        'title',
        'slug',
        'target_amount',
        'deadline_at',
        'status',
        'category',
        'cover_url',
        'description',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
        'target_amount' => 'integer',
    ];

    // Relasi
    public function donations() { return $this->hasMany(Donation::class); }
    public function owner()     { return $this->belongsTo(User::class, 'owner_id'); }

    public function getRouteKeyName(): string { return 'slug'; }

    public function scopePublished($q){ return $q->where('status','published'); }

    // Atribut bantu (tetap dipakai bila dibutuhkan di view lain)
    public function getCollectedAttribute(): int
    {
        return (int) $this->donations()->where('status','settlement')->sum('amount');
    }

    public function getProgressPctAttribute(): int
    {
        $goal = (int) ($this->target_amount ?? 0);
        if ($goal <= 0) return 0;
        return min(100, (int) floor($this->collected * 100 / $goal));
    }

    protected static function booted()
    {
        static::creating(function($m){
            if (empty($m->slug)) {
                $m->slug = Str::slug(($m->title ?: Str::random(6)).'-'.Str::lower(Str::random(4)));
            }
        });
    }
}
