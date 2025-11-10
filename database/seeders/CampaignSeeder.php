<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\User;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        // cari satu user untuk dijadikan owner
        $ownerId = User::value('id');
        if (!$ownerId) {
            // jika belum ada user sama sekali, buat dummy
            $ownerId = User::factory()->create([
                'name' => 'Admin Yayasan',
                'email' => 'admin@example.com',
                // password akan dibuat oleh factory default
            ])->id;
        }

        Campaign::firstOrCreate(
            ['slug' => 'bantu-pendidikan-anak'],
            [
                'title'       => 'Bantu Pendidikan Anak Pelosok',
                'excerpt'     => 'Akses buku & beasiswa untuk adik-adik di pelosok.',
                'description' => 'Mari wujudkan mimpi mereka melalui akses pendidikan yang layak.',
                'goal_amount' => 50000000,
                'status'      => 'published',
                'owner_id'    => $ownerId, // ⬅️ wajib diisi
            ]
        );
    }
}
