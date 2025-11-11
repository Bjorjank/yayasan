<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Identitas donor
            if (!Schema::hasColumn('donations', 'donor_name')) {
                $table->string('donor_name', 120)->nullable();
            }
            if (!Schema::hasColumn('donations', 'donor_email')) {
                $table->string('donor_email', 160)->nullable();
            }
            if (!Schema::hasColumn('donations', 'donor_whatsapp')) {
                $table->string('donor_whatsapp', 32)->nullable();
            }

            // Nominal & status
            if (!Schema::hasColumn('donations', 'amount')) {
                $table->unsignedBigInteger('amount')->default(0);
            }
            if (!Schema::hasColumn('donations', 'status')) {
                $table->string('status', 32)->default('pending')->index();
            }

            // Data pembayaran
            if (!Schema::hasColumn('donations', 'payment_ref')) {
                $table->string('payment_ref', 191)->nullable()->unique();
            }
            if (!Schema::hasColumn('donations', 'payment_method')) {
                $table->string('payment_method', 64)->nullable();
            }
            if (!Schema::hasColumn('donations', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
            if (!Schema::hasColumn('donations', 'payload')) {
                $table->json('payload')->nullable();
            }

            // FK dasar (jika belum ada) — opsional
            if (!Schema::hasColumn('donations', 'campaign_id')) {
                $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('donations', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
        });

        // Set default donor_name = 'Hamba ALLAH' untuk MySQL/MariaDB
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb']) && Schema::hasColumn('donations', 'donor_name')) {
            DB::statement("ALTER TABLE donations MODIFY donor_name VARCHAR(120) NULL DEFAULT 'Hamba ALLAH'");
        }
    }

    public function down(): void
    {
        // Revert default donor_name (opsional)
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb']) && Schema::hasColumn('donations', 'donor_name')) {
            DB::statement("ALTER TABLE donations MODIFY donor_name VARCHAR(120) NULL DEFAULT NULL");
        }

        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'donor_whatsapp')) $table->dropColumn('donor_whatsapp');
            // Kolom lain biarkan (sudah dipakai sistem)
        });
    }
};
