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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_code')->unique()->nullable();
            $table->enum('status', ['completed', 'pending', 'closed'])->default('pending');

            $table->unsignedBigInteger('librarian_id')->nullable();
            $table->unsignedBigInteger('section_id');
            $table->dateTime('closed_at')->nullable();
            $table->json('logs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
