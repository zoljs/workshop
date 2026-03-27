<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('workshop_sessions')->cascadeOnDelete();
            $table->integer('headcount');
            $table->string('payment_ref')->nullable();
            $table->integer('amount_paid')->nullable();
            $table->string('status')->default('confirmed');
            $table->timestamps();
            $table->unique(['user_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
