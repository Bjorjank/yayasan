<?php

namespace App\Services\Payments;

use App\Models\Donation;
use App\Services\Payments\Contracts\PaymentGateway;
use Illuminate\Support\Str;

class FakeGateway implements PaymentGateway
{
    protected int $expireMinutes;
    protected string $prefix;

    public function __construct(array $config = [])
    {
        $this->expireMinutes = (int)($config['expire_minutes'] ?? 60);
        $this->prefix = (string)($config['code_prefix'] ?? 'SIM');
    }

    public function create(Donation $donation): array
    {
        // generate kode pembayaran sederhana
        $ref = sprintf('%s-%s-%06d', $this->prefix, now()->format('YmdHis'), $donation->id);

        $payload = [
            'payment_ref'    => $ref,
            'payment_method' => 'virtual_account',
            'instructions'   => 'Silakan transfer ke VA 8888 00 123456 a.n. Yayasan Demo. Nominal harus tepat.',
            'expires_at'     => now()->addMinutes($this->expireMinutes),
        ];

        $donation->update([
            'payment_ref'    => $payload['payment_ref'],
            'payment_method' => $payload['payment_method'],
            'status'         => 'pending',
            'payload'        => [
                'fake'   => true,
                'guide'  => $payload['instructions'],
                'expire' => (string) $payload['expires_at'],
            ],
        ]);

        return $payload;
    }

    public function settle(Donation $donation): Donation
    {
        $donation->update([
            'status'  => 'settlement',
            'paid_at' => now(),
        ]);
        return $donation;
    }

    public function cancel(Donation $donation): Donation
    {
        $donation->update([
            'status' => 'cancel',
        ]);
        return $donation;
    }

    public function expire(Donation $donation): Donation
    {
        $donation->update([
            'status' => 'expire',
        ]);
        return $donation;
    }
}
