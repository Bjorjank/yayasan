<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
// app/Events/MessageSent.php
use App\Models\ChatMessage;

class MessageSent implements ShouldBroadcast {
    public function __construct(public ChatMessage $message) {}
    public function broadcastOn(): Channel {
        return new Channel('room.'.$this->message->room_id); // public channel
        // pakai PrivateChannel('room.'.$id) bila butuh authorization
    }
    public function broadcastAs(): string { return 'message.sent'; }
    public function broadcastWith(): array {
        return [
            'id'=>$this->message->id,
            'room_id'=>$this->message->room_id,
            'sender_id'=>$this->message->sender_id,
            'message'=>$this->message->message,
            'created_at'=>$this->message->created_at->toIso8601String(),
        ];
    }
}

