<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@yayasan.test',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                '_role' => 'superadmin',
            ],
            [
                'name' => 'Admin Yayasan',
                'email' => 'admin@yayasan.test',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                '_role' => 'admin',
            ],
            [
                'name' => 'Relawan Satu',
                'email' => 'relawan1@yayasan.test',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                '_role' => 'volunteer',
            ],
            [
                'name' => 'Donatur Contoh',
                'email' => 'donatur@yayasan.test',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                '_role' => 'donor',
            ],
        ];

        DB::table('users')->upsert(
            array_map(fn($u) => collect($u)->except('_role')->all(), $users),
            ['email'], // unique
            ['name', 'password', 'email_verified_at', 'remember_token', 'updated_at']
        );

        // Assign roles (uses Spatie models)
        $userModels = \App\Models\User::whereIn('email', array_column($users, 'email'))->get()->keyBy('email');
        foreach ($users as $u) {
            $userModels[$u['email']]->syncRoles([$u['_role']]);
        }

        // Tambahkan beberapa pengguna donor tambahan
        for ($i = 1; $i <= 6; $i++) {
            $email = "donor{$i}@yayasan.test";
            $exists = \App\Models\User::where('email', $email)->exists();
            if (!$exists) {
                $usr = \App\Models\User::create([
                    'name' => "Donor {$i}",
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'email_verified_at' => $now,
                    'remember_token' => Str::random(10),
                ]);
                $usr->assignRole('donor');
            }
        }
    }
}
