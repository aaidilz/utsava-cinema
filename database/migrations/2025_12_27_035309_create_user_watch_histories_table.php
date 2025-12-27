<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_watch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('identifier_id')->index(); // Anime ID
            $table->string('anime_title')->nullable();
            $table->string('anime_poster')->nullable();
            $table->string('episode_number'); // e.g., "1", "2"
            $table->float('position')->default(0); // Progress in seconds
            $table->float('duration')->nullable(); // Total duration in seconds
            $table->timestamp('last_watched_at')->useCurrent();
            $table->unique(['user_id', 'identifier_id']); // One entry per anime per user (stores last watched episode)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_watch_histories');
    }
};
