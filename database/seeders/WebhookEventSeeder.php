<?php
// database/seeders/WebhookEventSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WebhookEventSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $payload1 = [
            'provider' => 'midtrans',
            'event' => 'transaction.status',
            'order_id' => 'ORD-EXAMPLE-' . now()->format('YmdHis'),
            'transaction_status' => 'settlement',
            'gross_amount' => '250000',
            'payment_type' => 'qris',
        ];

        DB::table('webhook_events')->insert([
            'provider' => 'midtrans',
            'event_id' => Str::uuid()->toString(),
            'payload_json' => json_encode($payload1),
            'status' => 'processed',
            'processed_at' => $now->subDay(),
            'created_at' => $now->subDay(),
            'updated_at' => $now->subDay(),
        ]);

        $payload2 = [
            'provider' => 'midtrans',
            'event' => 'transaction.status',
            'order_id' => 'ORD-EXPIRED-' . now()->format('YmdHis'),
            'transaction_status' => 'expire',
            'gross_amount' => '50000',
            'payment_type' => 'qris',
        ];

        DB::table('webhook_events')->insert([
            'provider' => 'midtrans',
            'event_id' => Str::uuid()->toString(),
            'payload_json' => json_encode($payload2),
            'status' => 'pending',
            'processed_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
