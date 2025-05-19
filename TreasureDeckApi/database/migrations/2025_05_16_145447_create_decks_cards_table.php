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
        Schema::create('decks_cards', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('deck_id');
            $table->unsignedBigInteger('card_version_id');
            $table->unsignedTinyInteger('quantity');
            $table->foreign('deck_id')->references('id')
                ->on('decks');
            $table->foreign('card_version_id')->references('id')
                ->on('cards_versions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decks_cards');
    }
};
