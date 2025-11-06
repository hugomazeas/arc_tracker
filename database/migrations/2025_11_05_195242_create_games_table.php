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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->json('arrow_data'); // Array of arrows with x, y coordinates and calculated scores
            $table->integer('base_score');
            $table->integer('bonus_score')->default(0);
            $table->integer('total_score');
            $table->timestamps();

            $table->index('created_at');
            $table->index(['player_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
