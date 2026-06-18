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
        Schema::create('branch_sections', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('section_head_id')->nullable(); // Nullable if section has no assigned librarian
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
        Schema::dropIfExists('branch_sections');
    }
};
