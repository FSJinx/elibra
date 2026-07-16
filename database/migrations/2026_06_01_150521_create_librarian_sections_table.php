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
        Schema::create('librarian_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('librarian_id');
            $table->unsignedBigInteger('branch_section_id');
            $table->boolean('isHead')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('librarian_sections');
    }
};
