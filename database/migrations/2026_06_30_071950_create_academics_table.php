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
        Schema::create('academics', function (Blueprint $table) {
            $table->id();
            $table->string('call_number');
            $table->string('language');
            $table->enum('category', ['undergraduate thesis', 'graduate thesis', 'case study', 'research paper', 'feasibility study']);
            $table->year('publication_year');
            $table->json('subjects')->nullable();
            
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('department_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academics');
    }
};
