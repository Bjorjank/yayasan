<?php
// database/seeders/CampaignSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\User;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Cari owner untuk campaign
        // Prioritas: admin1@yayasan.test (sesuai DatabaseSeeder di branch fix-frontend),
        // fallback: first user; jika tidak ada user sama sekali, buat 1 dummy owner.
        $ownerId = User::where('email', 'admin1@yayasan.test')->value('id')
            ?? User::value('id');

        if (!$ownerId) {
            $owner = User::factory()->create([
                'name'  => 'Admin Yayasan',
                'email' => 'admin@example.com',
            ]);
            $ownerId = $owner->id;
        }

        $rows = [
            [
                'owner_id'      => $ownerId,
                'title'         => 'Bantuan Pendidikan Anak Dhuafa',
                'slug'          => Str::slug('Bantuan Pendidikan Anak Dhuafa'),
                'target_amount' => 100_000_000,
                'deadline_at'   => $now->copy()->addMonths(2),
                'status'        => 'published',
                'category'      => 'pendidikan',
                'cover_url'     => 'https://images.unsplash.com/photo-1558021212-51b6ecfa0db9?q=80&w=1600&auto=format&fit=crop',
                'description'   => 'Program beasiswa dan perlengkapan sekolah untuk anak dhuafa.',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'owner_id'      => $ownerId,
                'title'         => 'Donasi Kemanusiaan Bencana Alam',
                'slug'          => Str::slug('Donasi Kemanusiaan Bencana Alam'),
                'target_amount' => 250_000_000,
                'deadline_at'   => $now->copy()->addMonths(1),
                'status'        => 'published',
                'category'      => 'kemanusiaan',
                'cover_url'     => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1600&auto=format&fit=crop',
                'description'   => 'Bantuan logistik, kesehatan, dan hunian sementara.',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'owner_id'      => $ownerId,
                'title'         => 'Pemberdayaan UMKM Ibu Rumah Tangga',
                'slug'          => Str::slug('Pemberdayaan UMKM Ibu Rumah Tangga'),
                'target_amount' => 150_000_000,
                'deadline_at'   => $now->copy()->addMonths(3),
                'status'        => 'draft',
                'category'      => 'pemberdayaan',
                'cover_url'     => 'https://images.unsplash.com/photo-1536924940846-227afb31e2a5?q=80&w=1600&auto=format&fit=crop',
                'description'   => 'Pelatihan, modal mikro, dan pendampingan bisnis.',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        // Upsert berdasarkan slug (unik)
        // Kolom yang di-update saat konflik: selain slug & created_at.
        Campaign::upsert(
            $rows,
            ['slug'],
            [
                'owner_id',
                'title',
                'target_amount',
                'deadline_at',
                'status',
                'category',
                'cover_url',
                'description',
                'updated_at',
            ]
        );
    }
}
