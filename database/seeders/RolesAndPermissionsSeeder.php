<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Guard default 'web'
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            // Campaign
            'campaign.view',
            'campaign.create',
            'campaign.update',
            'campaign.delete',
            'campaign.publish',
            // Donation
            'donation.view',
            'donation.refund',
            // User/Admin
            'user.view',
            'user.manage',
            // Chat
            'chat.view',
            'chat.post',
            // Milestone
            'milestone.manage',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate([
                'name' => $p,
                'guard_name' => 'web',
            ]);
        }

        $roles = [
            'super-admin' => $permissions,
            'admin'       => ['campaign.view', 'campaign.create', 'campaign.update', 'campaign.publish', 'donation.view', 'chat.view', 'chat.post', 'milestone.manage', 'user.view'],
            'volunteer'   => ['campaign.view', 'chat.view', 'chat.post'],
            'donor'       => ['campaign.view', 'donation.view', 'chat.view', 'chat.post'],
        ];

        foreach ($roles as $role => $perms) {
            $r = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
            $r->syncPermissions($perms);
        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
