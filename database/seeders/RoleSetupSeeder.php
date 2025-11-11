<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan roles ada (guard 'web')
        foreach (['superadmin','admin','user'] as $r) {
            Role::findOrCreate($r, 'web');
        }

        // Mapping email → role (sesuaikan dengan yang kamu punya)
        $map = [
            'superadmin@yayasan.test' => 'superadmin',
            'admin1@yayasan.test'     => 'admin',
            'admin2@yayasan.test'     => 'admin',
            'user1@yayasan.test'      => 'user',
            'user2@yayasan.test'      => 'user',
            'user3@yayasan.test'      => 'user',
        ];

        foreach ($map as $email => $role) {
            if ($u = User::where('email',$email)->first()) {
                // gantikan semua role lama dengan yang ini
                $u->syncRoles([$role]);
            }
        }
    }
}
