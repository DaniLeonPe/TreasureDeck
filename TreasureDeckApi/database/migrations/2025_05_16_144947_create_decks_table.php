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
        Schema::create('decks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('leader_card_version_id');
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->string('name');
            $table->foreign('leader_card_version_id')->references('id')
                ->on('cards_versions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decks');
    }
};
