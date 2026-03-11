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
        Schema::create('commentables', function (Blueprint $table) {
            $table->foreignId('comment_id')
                ->constrained('comments')
                ->references('id');
            $table->unsignedInteger('commentable_id');
            $table->string('commentable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentables');
    }
};
