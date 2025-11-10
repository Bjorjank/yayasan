<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Uncomment kalau pakai spatie/laravel-permission
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name','email','password',
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ---------- Relations ---------- */
    public function ownedCampaigns()
    {
        return $this->hasMany(Campaign::class, 'owner_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function chatParticipations()
    {
        return $this->hasMany(ChatParticipant::class, 'user_id');
    }
}
