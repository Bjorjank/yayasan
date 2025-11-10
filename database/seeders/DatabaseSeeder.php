<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
