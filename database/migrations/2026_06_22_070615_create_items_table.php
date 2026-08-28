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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description');
            $table->string('call_number')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('electronic_file')->nullable();
            $table->json('keywords');
            
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('item_type_category_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('language_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
