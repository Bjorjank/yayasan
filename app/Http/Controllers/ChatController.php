<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ChatController.php
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Models\User;
use App\Models\ChatParticipant;

class ChatController extends Controller {
    public function room(ChatRoom $room) {
        // cek kepesertaan bila private
        $messages = ChatMessage::where('room_id',$room->id)->latest()->limit(50)->get()->reverse();
        return view('chat.room', compact('room','messages'));
    }

    public function send(Request $req, ChatRoom $room)
    {
        $req->validate(['message' => 'required|string|max:2000']);

        // Pastikan pengirim adalah peserta room (aman walau channel public)
        $isParticipant = $room->participants()
            ->where('user_id', auth()->id())->exists();

        if (!$isParticipant) {
            return response()->json(['ok' => false, 'error' => 'FORBIDDEN'], 403);
        }

        $msg = ChatMessage::create([
            'room_id'   => $room->id,
            'sender_id' => auth()->id(),
            'message'   => $req->input('message'),
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        // KEMBALIKAN JSON, BUKAN redirect/back()
        return response()->json([
            'ok'   => true,
            'id'   => $msg->id,
            'sent' => $msg->created_at?->toIso8601String(),
        ]);
    }

public function dm(User $user)
{
    $me = auth()->id();
    if (!$me) {
        return response()->json(['ok'=>false,'error'=>'UNAUTHENTICATED'], 401);
    }
    if ($user->id === $me) {
        return response()->json(['ok'=>false,'error'=>'CANNOT_DM_SELF'], 422);
    }

    $roomId = ChatRoom::query()
        ->where('type', ChatRoom::TYPE_DIRECT)
        ->whereHas('participants', fn($q) => $q->where('user_id', $me))
        ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
        ->value('id');

    if (!$roomId) {
        $room = ChatRoom::create(['type' => ChatRoom::TYPE_DIRECT, 'campaign_id' => null]);
        ChatParticipant::create(['room_id' => $room->id, 'user_id' => $me]);
        ChatParticipant::create(['room_id' => $room->id, 'user_id' => $user->id]);
        $roomId = $room->id;
    }

    return response()->json(['ok'=>true,'room_id'=>$roomId]);
}


public function users(Request $req)
{
    $me = auth()->id();
    $q  = trim((string) $req->query('q', ''));
    $take = min(20, (int) $req->query('take', 10));

    $users = \App\Models\User::query()
        ->when($q !== '', function ($w) use ($q) {
            $w->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        })
        ->where('id', '!=', $me)
        ->orderBy('name')
        ->limit($take)
        ->get(['id','name','email']);

    return response()->json([
        'ok' => true,
        'users' => $users->map(fn($u)=>[
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
        ])->values(),
    ]);
}

public function messages(ChatRoom $room, Request $req)
{
    $me = auth()->id();
    // batasi hanya participant yang boleh akses
    $isParticipant = $room->participants()->where('user_id', $me)->exists();
    if (!$isParticipant) {
        return response()->json(['ok'=>false, 'error'=>'FORBIDDEN'], 403);
    }

    $take = min(100, (int) $req->query('take', 50));
    $msgs = \App\Models\ChatMessage::where('room_id', $room->id)
        ->latest()->limit($take)->get()->reverse()
        ->map(fn($m)=>[
            'id'         => $m->id,
            'sender_id'  => $m->sender_id,
            'message'    => $m->message,
            'created_at' => $m->created_at?->toIso8601String(),
        ])->values();

    return response()->json(['ok'=>true, 'messages'=>$msgs]);
}


}
