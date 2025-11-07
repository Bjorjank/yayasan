<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    // type: group|direct
    public const TYPE_GROUP  = 'group';
    public const TYPE_DIRECT = 'direct';

    /* ---------- Relations ---------- */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'room_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }

    /* ---------- Helpers ---------- */
    public function hasUser(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
