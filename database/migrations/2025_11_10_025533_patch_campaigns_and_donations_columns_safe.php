<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $t) {
                if (!Schema::hasColumn('campaigns','excerpt'))     $t->string('excerpt',300)->nullable();
                if (!Schema::hasColumn('campaigns','goal_amount')) $t->unsignedBigInteger('goal_amount')->default(0);
                if (!Schema::hasColumn('campaigns','status'))      $t->string('status',20)->default('draft')->index();
                if (!Schema::hasColumn('campaigns','deadline_at')) $t->timestamp('deadline_at')->nullable();
                if (!Schema::hasColumn('campaigns','cover_path'))  $t->string('cover_path')->nullable();
                if (!Schema::hasColumn('campaigns','created_by'))  $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        if (Schema::hasTable('donations')) {
            Schema::table('donations', function (Blueprint $t) {
                if (!Schema::hasColumn('donations','status'))         $t->string('status',20)->default('pending')->index();
                if (!Schema::hasColumn('donations','payment_ref'))    $t->string('payment_ref')->nullable();
                if (!Schema::hasColumn('donations','payment_method')) $t->string('payment_method')->nullable();
                if (!Schema::hasColumn('donations','paid_at'))        $t->timestamp('paid_at')->nullable();
                if (!Schema::hasColumn('donations','payload'))        $t->json('payload')->nullable();
            });
        }
    }

    public function down(): void
    {
        // biasanya tidak perlu rollback aman untuk patch seperti ini
    }
};
