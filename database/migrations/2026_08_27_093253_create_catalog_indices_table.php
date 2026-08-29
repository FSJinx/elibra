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
        Schema::create('catalog_indices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('item_id')->nullable();
                
            // semantic search -> content
            $table-> longText('content');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('item_type_id')->nullable();
            $table->unsignedBigInteger('item_type_category_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            $table->year('publication_year')->nullable();
            $table->string('language')->nullable();

            // Will be used later for embeddings
            // $table->json('embedding')->nullable();
            $table->timestamp('indexed_at')->nullable();

            $table->timestamps();

            $table->index('branch_id');
            $table->index('item_type_id');
            $table->index('item_type_category_id');
            $table->index('department_id');
            $table->index('publication_year');
            $table->index('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_indexes');
    }
};
