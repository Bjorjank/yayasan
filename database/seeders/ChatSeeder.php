<?php
// database/seeders/ChatSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // --- Pastikan minimal 2 user ada
        $ensure = [
            ['name' => 'Super Admin', 'email' => 'superadmin@yayasan.test'],
            ['name' => 'Admin Satu',  'email' => 'admin1@yayasan.test'],
        ];
        $ids = [];
        foreach ($ensure as $e) {
            $u = User::firstOrCreate(
                ['email' => $e['email']],
                [
                    'name'              => $e['name'],
                    'password'          => bcrypt(env('SEEDER_DEFAULT_PASSWORD', 'secret123')),
                    'email_verified_at' => now(),
                ]
            );
            $ids[] = $u->id;
        }
        if (count($ids) < 2) {
            $u = User::firstOrCreate(
                ['email' => 'user@yayasan.test'],
                [
                    'name'              => 'User',
                    'password'          => bcrypt(env('SEEDER_DEFAULT_PASSWORD', 'secret123')),
                    'email_verified_at' => now(),
                ]
            );
            $ids[] = $u->id;
        }
        $userA = $ids[0];
        $userB = $ids[1] ?? $ids[0];

        // --- CHAT ROOMS (kolom dinamis)
        $roomCols     = Schema::getColumnListing('chat_rooms');
        $roomNameCol  = collect(['name', 'title', 'label'])->first(fn($c) => in_array($c, $roomCols, true));
        $hasIsPrivate = in_array('is_private', $roomCols, true);

        $roomPayload = ['updated_at' => $now];
        if ($roomNameCol) $roomPayload[$roomNameCol] = 'Koordinasi Yayasan';
        if ($hasIsPrivate) $roomPayload['is_private'] = false;

        DB::table('chat_rooms')->updateOrInsert(
            ['id' => 1],
            array_merge($roomPayload, ['created_at' => $now])
        );

        // --- CHAT PARTICIPANTS
        $partCols = Schema::getColumnListing('chat_participants');
        // Wajib ada room_id & user_id (kebanyakan skema seperti ini)
        DB::table('chat_participants')->updateOrInsert(
            ['room_id' => 1, 'user_id' => $userA],
            ['last_read_at' => $now, 'created_at' => $now, 'updated_at' => $now]
        );
        DB::table('chat_participants')->updateOrInsert(
            ['room_id' => 1, 'user_id' => $userB],
            ['last_read_at' => $now, 'created_at' => $now, 'updated_at' => $now]
        );

        // --- CHAT MESSAGES (deteksi kolom dinamis)
        $msgCols       = Schema::getColumnListing('chat_messages');
        $textCol       = collect(['content', 'message', 'body', 'text'])->first(fn($c) => in_array($c, $msgCols, true));
        $senderIdCol   = collect(['sender_id', 'user_id', 'from_user_id', 'author_id'])->first(fn($c) => in_array($c, $msgCols, true));
        $roomIdCol     = in_array('room_id', $msgCols, true) ? 'room_id' : null;
        $hasUuid       = in_array('uuid', $msgCols, true);

        // Jika tidak ada kolom teks atau tidak ada kolom sender id, hentikan agar tidak error
        if (!$textCol || !$senderIdCol || !$roomIdCol) {
            // Tidak bisa seed pesan tanpa kolom-kolom penting ini
            return;
        }

        $messages = [
            [
                $textCol  => 'Halo tim, mari sinkron progres kampanye minggu ini.',
                'sender'  => $userA,
                'created' => $now->copy()->subMinutes(5),
            ],
            [
                $textCol  => 'Siap, nanti saya update target dan laporan harian.',
                'sender'  => $userB,
                'created' => $now->copy()->subMinutes(3),
            ],
            [
                $textCol  => 'Noted. Kita push postingan IG jam 19:00.',
                'sender'  => $userA,
                'created' => $now->copy()->subMinute(),
            ],
        ];

        foreach ($messages as $m) {
            $payload = [
                $textCol         => $m[$textCol],
                $senderIdCol     => $m['sender'],
                $roomIdCol       => 1,
                'created_at'     => $m['created'],
                'updated_at'     => $m['created'],
            ];
            if ($hasUuid) {
                $payload['uuid'] = (string) Str::uuid();
            }
            DB::table('chat_messages')->insert($payload);
        }
    }
}
