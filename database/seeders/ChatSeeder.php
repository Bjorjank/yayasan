<?php
// database/seeders/ChatSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $campaign = \App\Models\Campaign::where('slug', 'bantuan-pendidikan-anak-dhuafa')->first();
        $adminId  = \App\Models\User::where('email', 'admin@yayasan.test')->value('id');
        $donorId  = \App\Models\User::where('email', 'donatur@yayasan.test')->value('id');

        // Room group untuk campaign
        $roomId = DB::table('chat_rooms')->insertGetId([
            'type' => 'group',
            'campaign_id' => $campaign?->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Peserta: admin + donor + beberapa donorX
        $participants = [$adminId, $donorId];
        $extra = \App\Models\User::where('email', 'like', 'donor%@yayasan.test')->limit(3)->pluck('id')->all();
        $participants = array_values(array_unique(array_merge($participants, $extra)));

        foreach ($participants as $uid) {
            DB::table('chat_participants')->insert([
                'room_id' => $roomId,
                'user_id' => $uid,
                'last_read_at' => $now->copy()->subMinutes(rand(0, 60)),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Beberapa pesan contoh
        $messages = [
            [
                'room_id' => $roomId,
                'sender_id' => $adminId,
                'message' => 'Halo semuanya, terima kasih atas dukungannya untuk program pendidikan ini.',
                'file_url' => null,
                'meta' => json_encode(['type' => 'text']),
                'created_at' => $now->copy()->subMinutes(50),
                'updated_at' => $now->copy()->subMinutes(50),
            ],
            [
                'room_id' => $roomId,
                'sender_id' => $donorId,
                'message' => 'Saya baru berdonasi. Semoga membantu adik-adik.',
                'file_url' => null,
                'meta' => json_encode(['type' => 'text']),
                'created_at' => $now->copy()->subMinutes(40),
                'updated_at' => $now->copy()->subMinutes(40),
            ],
            [
                'room_id' => $roomId,
                'sender_id' => $adminId,
                'message' => null,
                'file_url' => 'https://picsum.photos/seed/rapat/800/400',
                'meta' => json_encode(['type' => 'image']),
                'created_at' => $now->copy()->subMinutes(35),
                'updated_at' => $now->copy()->subMinutes(35),
            ],
        ];

        DB::table('chat_messages')->insert($messages);

        // Tambah 1 room direct admin <-> donor
        $directRoomId = DB::table('chat_rooms')->insertGetId([
            'type' => 'direct',
            'campaign_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ([$adminId, $donorId] as $uid) {
            DB::table('chat_participants')->insert([
                'room_id' => $directRoomId,
                'user_id' => $uid,
                'last_read_at' => $now->copy()->subMinutes(rand(0, 20)),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('chat_messages')->insert([
            'room_id' => $directRoomId,
            'sender_id' => $donorId,
            'message' => 'Halo admin, saya ingin konfirmasi donasi saya sudah masuk?',
            'file_url' => null,
            'meta' => json_encode(['type' => 'text']),
            'created_at' => $now->copy()->subMinutes(10),
            'updated_at' => $now->copy()->subMinutes(10),
        ]);
    }
}
