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
        // Password default untuk akun awal (bisa override via .env)
        $defaultPassword = env('SEEDER_DEFAULT_PASSWORD', 'secret123');

        // 1) Seed akun dasar (wajib ada duluan karena dipakai relasi)
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

        // 2) Roles/Permissions (jika ada)
        if (class_exists(\Database\Seeders\RoleSetupSeeder::class)) {
            $this->call(RoleSetupSeeder::class);
        }

        // 3) Seeder lain — aman karena users sudah ada
        $optionalSeeders = [
            \Database\Seeders\CampaignSeeder::class,
            \Database\Seeders\DonationSeeder::class,
            \Database\Seeders\ChatSeeder::class,
            \Database\Seeders\MilestoneSeeder::class,
            \Database\Seeders\WebhookEventSeeder::class,

            // kompatibilitas/legacy bila masih dipakai
            \Database\Seeders\RolesAndPermissionsSeeder::class,
            \Database\Seeders\UserSeeder::class,
        ];

        foreach ($optionalSeeders as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }
}
