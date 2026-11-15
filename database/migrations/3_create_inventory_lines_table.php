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
        Schema::create('inventory_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('accession_id');

            $table->string('current_status');
            $table->unsignedBigInteger('current_section_id')->nullable();

            $table->enum('new_status', ['available', 'reserved', 'borrowed', 'lost', 'missing', 'archived', 'condemned'])->default('available');
            $table->unsignedBigInteger('new_section_id')->nullable();
            
            $table->unsignedBigInteger('verifier_id')->nullable();
            $table->dateTime('verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_lines');
    }
};
