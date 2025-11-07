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
    $isParticipant = $room->participants()->where('user_id', $me)->exists();
    if (!$isParticipant) {
        return response()->json(['ok'=>false, 'error'=>'FORBIDDEN'], 403);
    }

    // ✅ tandai read
    \App\Models\ChatParticipant::where('room_id', $room->id)
        ->where('user_id', $me)
        ->update(['last_read_at' => now()]);

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

public function recent(Request $req)
{
    $me = auth()->id();

    // Ambil semua room DIRECT yang diikuti user ini
    $myRooms = \App\Models\ChatParticipant::query()
        ->where('user_id', $me)
        ->pluck('room_id');

    if ($myRooms->isEmpty()) {
        return response()->json(['ok'=>true, 'items'=>[]]);
    }

    // Ambil pesan terakhir per room
    $lastMessages = \App\Models\ChatMessage::query()
        ->selectRaw('room_id, MAX(id) as max_id')
        ->whereIn('room_id', $myRooms)
        ->groupBy('room_id');

    $latest = \DB::table('chat_messages as m')
        ->joinSub($lastMessages, 'lm', fn($j)=>$j->on('m.room_id','=','lm.room_id')->on('m.id','=','lm.max_id'))
        ->select('m.room_id','m.message','m.sender_id','m.created_at')
        ->get()
        ->keyBy('room_id');

    // Ambil peer (lawan chat) untuk tiap room direct
    $rooms = \App\Models\ChatRoom::query()
        ->whereIn('id', $myRooms)
        ->where('type', \App\Models\ChatRoom::TYPE_DIRECT)
        ->get(['id']);

    $items = [];

    foreach ($rooms as $r) {
        // Tentukan peer = participant lain
        $peerId = \App\Models\ChatParticipant::where('room_id',$r->id)
            ->where('user_id','!=',$me)->value('user_id');

        $peer = $peerId ? \App\Models\User::find($peerId, ['id','name','email']) : null;

        $lm = $latest[$r->id] ?? null;

        // Hitung unread (pesan masuk setelah last_read_at)
        $lastReadAt = \App\Models\ChatParticipant::where('room_id',$r->id)
            ->where('user_id',$me)->value('last_read_at');

        $unread = 0;
        if ($lastReadAt) {
            $unread = \App\Models\ChatMessage::where('room_id',$r->id)
                ->where('sender_id','!=',$me)
                ->where('created_at','>', $lastReadAt)
                ->count();
        } else {
            // Jika tidak pernah baca, hitung semua pesan lawan sebagai unread
            $unread = \App\Models\ChatMessage::where('room_id',$r->id)
                ->where('sender_id','!=',$me)
                ->count();
        }

        $items[] = [
            'room_id'     => $r->id,
            'peer_id'     => $peer?->id,
            'title'       => $peer?->name ?? 'Percakapan',
            'subtitle'    => $peer?->email,
            'last_message'=> $lm?->message,
            'last_at'     => $lm?->created_at ? \Illuminate\Support\Carbon::parse($lm->created_at)->toIso8601String() : null,
            'unread'      => $unread,
        ];
    }

    // Urutkan recent: terbaru di atas
    usort($items, function($a,$b){
        return strcmp($b['last_at'] ?? '1970-01-01', $a['last_at'] ?? '1970-01-01');
    });

    // Batasi (opsional)
    $limit = (int) $req->query('take', 20);
    $items = array_slice($items, 0, max(1, min(100, $limit)));

    return response()->json(['ok'=>true, 'items'=>$items]);
}



}
