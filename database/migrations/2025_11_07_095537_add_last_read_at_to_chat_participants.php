<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah kolom hanya jika belum ada
        if (! Schema::hasColumn('chat_participants', 'last_read_at')) {
            Schema::table('chat_participants', function (Blueprint $table) {
                $table->timestamp('last_read_at')->nullable()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        // Hapus kolom hanya jika ada
        if (Schema::hasColumn('chat_participants', 'last_read_at')) {
            Schema::table('chat_participants', function (Blueprint $table) {
                $table->dropColumn('last_read_at');
            });
        }
    }
};
