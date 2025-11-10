<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;


use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $defaultPassword = 'secret123';

        // 1) Seed users dasar
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
                    'name' => $acc['name'],
                    'password' => Hash::make($defaultPassword),
                    'email_verified_at' => now(),
                ]
            );
        }

        // 2) Selalu assign roles (WAJIB, jangan conditional)
        $this->call(RoleSetupSeeder::class);

        // 3) (Opsional) seed campaign dsb.
        if (class_exists(\Database\Seeders\CampaignSeeder::class)) {
            $this->call(CampaignSeeder::class);
        }

        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CampaignSeeder::class,
            DonationSeeder::class,
            ChatSeeder::class,
            MilestoneSeeder::class,
            WebhookEventSeeder::class,
        ]);

    }
}
