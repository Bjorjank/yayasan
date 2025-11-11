<?php
// database/migrations/2025_11_11_000900_fix_order_id_on_donations.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Jika kolom belum ada → tambahkan
        if (!Schema::hasColumn('donations', 'order_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->string('order_id', 64)->nullable()->unique()->after('status');
            });
        } else {
            // Jika sudah ada tapi NOT NULL / tipe lain → ubah ke VARCHAR(64) NULL UNIQUE (MySQL/MariaDB)
            $driver = DB::getDriverName();
            if (in_array($driver, ['mysql','mariadb'])) {
                // unique key mungkin belum ada — pastikan ada
                // (kalau sudah ada, MySQL akan abaikan)
                try { DB::statement("ALTER TABLE donations ADD UNIQUE KEY donations_order_id_unique (order_id)"); } catch (\Throwable $e) {}

                // Ubah tipe & nullability (tanpa Doctrine DBAL)
                DB::statement("ALTER TABLE donations MODIFY order_id VARCHAR(64) NULL");
            }
        }
    }

    public function down(): void
    {
        // Tidak kita drop agar aman. Kalau perlu rollback:
        // Schema::table('donations', function (Blueprint $table) {
        //     $table->dropUnique('donations_order_id_unique');
        //     $table->dropColumn('order_id');
        // });
    }
};
