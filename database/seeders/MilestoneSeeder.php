<?php
// database/seeders/MilestoneSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MilestoneSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $campaign = \App\Models\Campaign::where('slug', 'bantuan-pendidikan-anak-dhuafa')->first();
        if (!$campaign) return;

        $rows = [
            [
                'campaign_id' => $campaign->id,
                'title' => 'Kickoff Program & Survei Penerima',
                'body' => 'Tim melakukan survei untuk menentukan penerima bantuan.',
                'published_at' => $now->copy()->subDays(5),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'campaign_id' => $campaign->id,
                'title' => 'Distribusi Perlengkapan Sekolah Tahap 1',
                'body' => 'Telah disalurkan ke 30 siswa tingkat SD.',
                'published_at' => $now->copy()->subDays(2),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('milestones')->insert($rows);
    }
}
