<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('chat_rooms', function (Blueprint $t) {
            $t->id();
            $t->string('type')->default('group'); // group|direct
            $t->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('chat_participants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('room_id')->constrained('chat_rooms')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->timestamp('last_read_at')->nullable();
            $t->unique(['room_id','user_id']);
            $t->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('room_id')->constrained('chat_rooms')->cascadeOnDelete();
            $t->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $t->text('message')->nullable();
            $t->string('file_url')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
            $t->index(['room_id','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms_and_messages');
    }
};
