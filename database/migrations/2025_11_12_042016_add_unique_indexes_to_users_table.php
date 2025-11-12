<?php
// database/migrations/2025_11_12_000001_add_unique_indexes_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan belum ada index bernama sama di environmentmu
            if (! $this->indexExists('users', 'users_email_unique')) {
                $table->unique('email');
            }
            if (! $this->indexExists('users', 'users_name_unique')) {
                $table->unique('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'users_email_unique')) {
                $table->dropUnique('users_email_unique');
            }
            if ($this->indexExists('users', 'users_name_unique')) {
                $table->dropUnique('users_name_unique');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(Schema::getIndexes($table))->pluck('name')->contains($index);
    }
};
