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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->integer('points')->default(0);
            $table->timestamp('banned_until')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nickname', 'birth_date', 'gender', 'points', 'banned_until']);
            $table->dropSoftDeletes();
        });
    }
};
