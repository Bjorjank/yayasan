<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Password default untuk akun awal (boleh diganti via .env atau config bila perlu)
        $defaultPassword = 'secret123';

        // 1) Seed akun dasar (sesuai branch fix-frontend)
        $accounts = [
            ['name' => 'Super Admin', 'email' => 'superadmin@yayasan.test'],
            ['name' => 'Admin Satu',  'email' => 'admin1@yayasan.test'],
            ['name' => 'Admin Dua',   'email' => 'admin2@yayasan.test'],
            ['name' => 'User Satu',   'email' => 'user1@yayasan.test'],
            ['name' => 'User Dua',    'email' => 'user2@yayasan.test'],
            ['name' => 'User Tiga',   'email' => 'user3@yayasan.test'],
        ];

        foreach ($accounts as $acc) {
            User::updateOrCreate(
                ['email' => $acc['email']],
                [
                    'name'              => $acc['name'],
                    'password'          => Hash::make($defaultPassword),
                    'email_verified_at' => now(),
                ]
            );
        }

        // 2) Set up roles & permissions (Wajib, sesuai branch fix-frontend)
        // Pastikan seeder ini ada: database/seeders/RoleSetupSeeder.php
        $this->call(RoleSetupSeeder::class);

        // 3) Seeder lain bersifat opsional (dipanggil hanya jika ada kelasnya)
        // Ini mencegah error "Class ... does not exist" jika belum dibuat.
        $optionalSeeders = [
            CampaignSeeder::class,
            DonationSeeder::class,
            ChatSeeder::class,
            MilestoneSeeder::class,
            WebhookEventSeeder::class,

            // Jika Anda juga memiliki alternatif lama:
            // RolesAndPermissionsSeeder::class,
            // UserSeeder::class,
        ];

        foreach ($optionalSeeders as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }
}
