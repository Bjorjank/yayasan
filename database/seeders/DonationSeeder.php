<?php
// database/seeders/DonationSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $campaigns = \App\Models\Campaign::whereIn('slug', [
            'bantuan-pendidikan-anak-dhuafa',
            'donasi-kemanusiaan-bencana-alam',
        ])->get()->keyBy('slug');

        $donors = \App\Models\User::where('email', 'like', 'donor%@yayasan.test')->pluck('id')->all();
        if (empty($donors)) {
            $donors = [\App\Models\User::where('email', 'donatur@yayasan.test')->value('id')];
        }

        $makeOrderId = fn() => 'ORD-' . Str::upper(Str::random(10)) . '-' . now()->format('YmdHis');

        $seed = [
            [
                'campaign_id' => $campaigns['bantuan-pendidikan-anak-dhuafa']->id ?? null,
                'user_id' => $donors[array_rand($donors)],
                'amount' => 250000,
                'payment_provider' => 'midtrans',
                'payment_method' => 'qris',
                'status' => 'settlement',
                'order_id' => $makeOrderId(),
                'external_ref' => Str::uuid()->toString(),
                'paid_at' => $now->copy()->subDays(2),
                'meta' => json_encode(['note' => 'Semoga bermanfaat']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'campaign_id' => $campaigns['bantuan-pendidikan-anak-dhuafa']->id ?? null,
                'user_id' => $donors[array_rand($donors)],
                'amount' => 1000000,
                'payment_provider' => 'midtrans',
                'payment_method' => 'va',
                'status' => 'pending',
                'order_id' => $makeOrderId(),
                'external_ref' => null,
                'paid_at' => null,
                'meta' => json_encode(['bank' => 'BCA']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'campaign_id' => $campaigns['donasi-kemanusiaan-bencana-alam']->id ?? null,
                'user_id' => $donors[array_rand($donors)],
                'amount' => 150000,
                'payment_provider' => 'midtrans',
                'payment_method' => 'ewallet',
                'status' => 'settlement',
                'order_id' => $makeOrderId(),
                'external_ref' => Str::uuid()->toString(),
                'paid_at' => $now->copy()->subDays(1),
                'meta' => json_encode(['wallet' => 'OVO']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'campaign_id' => $campaigns['donasi-kemanusiaan-bencana-alam']->id ?? null,
                'user_id' => $donors[array_rand($donors)],
                'amount' => 50000,
                'payment_provider' => 'midtrans',
                'payment_method' => 'qris',
                'status' => 'expire',
                'order_id' => $makeOrderId(),
                'external_ref' => null,
                'paid_at' => null,
                'meta' => json_encode(['expired' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Filter null campaign_id if any
        $seed = array_values(array_filter($seed, fn($r) => !empty($r['campaign_id'])));

        DB::table('donations')->insert($seed);
    }
}
