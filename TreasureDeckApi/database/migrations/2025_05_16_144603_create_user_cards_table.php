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
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('card_version_id');
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->foreign('card_version_id')->references('id')
                ->on('cards_versions');
            $table->integer('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
