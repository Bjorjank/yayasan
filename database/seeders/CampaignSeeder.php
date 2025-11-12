<?php
// database/seeders/CampaignSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Campaign;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Owner prioritas: admin1@yayasan.test → fallback: user pertama → kalau kosong, buat 1 user dummy
        $ownerId = User::where('email', 'admin1@yayasan.test')->value('id')
            ?? User::value('id');

        if (!$ownerId) {
            $ownerId = User::factory()->create([
                'name'  => 'Admin Yayasan',
                'email' => 'admin@example.com',
                // password by factory default
            ])->id;
        }

        // Data campaign konsisten (pakai kolom target_amount)
        $rows = [
            [
                'owner_id'      => $ownerId,
                'title'         => 'Bantu Pendidikan Anak Pelosok',
                'slug'          => 'bantu-pendidikan-anak',
                'excerpt'       => 'Akses buku & beasiswa untuk adik-adik di pelosok.',
                'description'   => 'Mari wujudkan mimpi mereka melalui akses pendidikan yang layak.',
                'cover_url'     => 'https://images.unsplash.com/photo-1558021212-51b6ecfa0db9?q=80&w=1600&auto=format&fit=crop',
                'category'      => 'pendidikan',
                'target_amount' => 50_000_000,
                'deadline_at'   => $now->copy()->addMonths(2),
                'status'        => 'published',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'owner_id'      => $ownerId,
                'title'         => 'Bantuan Pendidikan Anak Dhuafa',
                'slug'          => Str::slug('Bantuan Pendidikan Anak Dhuafa'),
                'excerpt'       => 'Program beasiswa & perlengkapan sekolah.',
                'description'   => 'Program beasiswa dan perlengkapan sekolah untuk anak dhuafa.',
                'cover_url'     => 'https://images.unsplash.com/photo-1558021212-51b6ecfa0db9?q=80&w=1600&auto=format&fit=crop',
                'category'      => 'pendidikan',
                'target_amount' => 100_000_000,
                'deadline_at'   => $now->copy()->addMonths(2),
                'status'        => 'published',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'owner_id'      => $ownerId,
                'title'         => 'Donasi Kemanusiaan Bencana Alam',
                'slug'          => Str::slug('Donasi Kemanusiaan Bencana Alam'),
                'excerpt'       => 'Logistik, kesehatan, hunian sementara.',
                'description'   => 'Bantuan logistik, kesehatan, dan hunian sementara.',
                'cover_url'     => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1600&auto=format&fit=crop',
                'category'      => 'kemanusiaan',
                'target_amount' => 250_000_000,
                'deadline_at'   => $now->copy()->addMonths(1),
                'status'        => 'published',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'owner_id'      => $ownerId,
                'title'         => 'Pemberdayaan UMKM Ibu Rumah Tangga',
                'slug'          => Str::slug('Pemberdayaan UMKM Ibu Rumah Tangga'),
                'excerpt'       => 'Pelatihan, modal mikro, pendampingan.',
                'description'   => 'Pelatihan, modal mikro, dan pendampingan bisnis.',
                'cover_url'     => 'https://images.unsplash.com/photo-1536924940846-227afb31e2a5?q=80&w=1600&auto=format&fit=crop',
                'category'      => 'pemberdayaan',
                'target_amount' => 150_000_000,
                'deadline_at'   => $now->copy()->addMonths(3),
                'status'        => 'draft',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        // Upsert berdasarkan slug (unik). Update semua kolom relevan kecuali slug & created_at.
        Campaign::upsert(
            $rows,
            ['slug'],
            [
                'owner_id',
                'title',
                'excerpt',
                'description',
                'cover_url',
                'category',
                'target_amount',
                'deadline_at',
                'status',
                'updated_at',
            ]
        );
    }
}
