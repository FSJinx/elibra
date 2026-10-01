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
            $table->text('description')->nullable();
            $table->string('call_number')->nullable();
            $table->string('electronic_file')->nullable();
            $table->json('keywords')->nullable();

            $table->string('edition')->nullable(); // Books
            $table->string('isbn_issn')->nullable(); // Books, Serials
            $table->string('copyright_year')->nullable(); // Books
            $table->string('doi')->nullable()->nullable(); // Theses, Serials
            $table->string('volume')->nullable(); // Serials
            $table->string('issue')->nullable(); // Serials
            $table->string('pages')->nullable(); // Books, Serials
            $table->unsignedBigInteger('department_id')->nullable(); // Theses
            
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('item_type_category_id');
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('library_id');

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
