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
        Schema::create('webhook_events', function (Blueprint $t) {
            $t->id();
            $t->string('provider');
            $t->string('event_id')->nullable();
            $t->json('payload_json');
            $t->string('status')->default('pending'); // pending|processed|failed
            $t->timestamp('processed_at')->nullable();
            $t->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
