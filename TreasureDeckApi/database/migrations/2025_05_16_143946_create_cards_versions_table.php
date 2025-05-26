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
        Schema::create('cards_versions', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('card_id');
            $table->string('versions')->nullable();
            $table->string('image_url');
            $table->decimal('min_price', 8,2);
            $table->decimal('avg_price', 8,2);
            $table->foreign('card_id')->references('id')
            ->on('cards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards_versions');
    }
};
