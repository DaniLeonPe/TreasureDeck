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
        Schema::create('decks_stats', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('deck_id');
            $table->foreign('deck_id')->references('id')
                ->on('decks');
            $table->integer('wins');
            $table->integer('losses');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decks_stats');
    }
};
