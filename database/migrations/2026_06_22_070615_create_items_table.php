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
            $table->string('subtitle');
            $table->text('description');
            $table->string('call_number')->nullable();
            $table->string('language');
            $table->year('publication_year');
            $table->string('keywords');
            $table->string('electronic_file')->nullable();
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('item_type_category_id');
            $table->unsignedBigInteger('branch_id');

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
