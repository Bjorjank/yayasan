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
        Schema::create('donations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->unsignedBigInteger('amount');
            $t->string('payment_provider')->default('midtrans');
            $t->string('payment_method')->nullable(); // qris|va|ewallet|cc
            $t->string('status')->default('pending'); // pending|settlement|expire|refund
            $t->string('order_id')->unique();
            $t->string('external_ref')->nullable();   // transaksi_id dari provider
            $t->timestamp('paid_at')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
            $t->index(['campaign_id','status']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
