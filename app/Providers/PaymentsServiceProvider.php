<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Payments\Contracts\PaymentGateway;
use App\Services\Payments\FakeGateway;

class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGateway::class, function ($app) {
            $cfg = config('payments');
            $driver = $cfg['driver'] ?? 'fake';

            // Untuk sekarang kita hanya binding 'fake'
            if ($driver === 'fake') {
                return new FakeGateway($cfg['drivers']['fake'] ?? []);
            }

            // default fallback: fake
            return new FakeGateway($cfg['drivers']['fake'] ?? []);
        });
    }
}
