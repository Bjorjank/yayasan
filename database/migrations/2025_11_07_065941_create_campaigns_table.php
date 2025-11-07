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
        Schema::create('campaigns', function (Blueprint $t) {
            $t->id();
            $t->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->unsignedBigInteger('target_amount')->default(0);
            $t->timestamp('deadline_at')->nullable();
            $t->string('status')->default('draft'); // draft|published|closed
            $t->string('category')->nullable();
            $t->string('cover_url')->nullable();
            $t->longText('description')->nullable();
            $t->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
