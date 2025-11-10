<?php
// database/seeders/CampaignSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $adminId = \App\Models\User::where('email', 'admin@yayasan.test')->value('id')
            ?? \App\Models\User::first()->id;

        $rows = [
            [
                'owner_id' => $adminId,
                'title' => 'Bantuan Pendidikan Anak Dhuafa',
                'slug' => 'bantuan-pendidikan-anak-dhuafa',
                'target_amount' => 100_000_000,
                'deadline_at' => $now->copy()->addMonths(2),
                'status' => 'published',
                'category' => 'pendidikan',
                'cover_url' => 'https://picsum.photos/seed/pendidikan/1200/600',
                'description' => 'Program beasiswa dan perlengkapan sekolah untuk anak dhuafa.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'owner_id' => $adminId,
                'title' => 'Donasi Kemanusiaan Bencana Alam',
                'slug' => 'donasi-kemanusiaan-bencana-alam',
                'target_amount' => 250_000_000,
                'deadline_at' => $now->copy()->addMonths(1),
                'status' => 'published',
                'category' => 'kemanusiaan',
                'cover_url' => 'https://picsum.photos/seed/kemanusiaan/1200/600',
                'description' => 'Bantuan logistik, kesehatan, dan hunian sementara.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'owner_id' => $adminId,
                'title' => 'Pemberdayaan UMKM Ibu Rumah Tangga',
                'slug' => 'pemberdayaan-umkm-irt',
                'target_amount' => 150_000_000,
                'deadline_at' => $now->copy()->addMonths(3),
                'status' => 'draft',
                'category' => 'pemberdayaan',
                'cover_url' => 'https://picsum.photos/seed/umkm/1200/600',
                'description' => 'Pelatihan, modal mikro, dan pendampingan bisnis.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('campaigns')->upsert($rows, ['slug'], [
            'owner_id',
            'title',
            'target_amount',
            'deadline_at',
            'status',
            'category',
            'cover_url',
            'description',
            'updated_at'
        ]);
    }
}
