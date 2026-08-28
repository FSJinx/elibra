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
        Schema::create('catalog_embeddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_index_id');

            $table->longText('embedding');
            $table->string('model')->nullable();
            $table->unsignedInteger('dimensions')->nullable();
            $table->timestamps();

            $table->unique('catalog_index_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_embeddings');
    }
};
