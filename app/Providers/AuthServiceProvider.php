<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Hanya admin & superadmin yang boleh akses panel admin
        Gate::define('access-admin', function ($user) {
            // Jika punya kolom ROLE string
            $role = strtolower((string) data_get($user, 'role', ''));
            if (in_array($role, ['admin', 'superadmin'], true)) {
                return true;
            }

            // Jika pakai flag boolean
            $isAdmin = (bool) data_get($user, 'is_admin', false);
            $isSA    = (bool) data_get($user, 'is_super_admin', false);
            return $isAdmin || $isSA;
        });
    }
}
