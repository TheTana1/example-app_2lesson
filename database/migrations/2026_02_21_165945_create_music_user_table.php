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
        Schema::create('music_user', function (Blueprint $table) {
            $table->foreignId('music_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }
//            $table->morphs('favoritesable');
//            $table->timestamps();
//
//            $table->unique(['user_id', 'favoritesable_id', 'favoritesable_type']);
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_user');
    }
};
