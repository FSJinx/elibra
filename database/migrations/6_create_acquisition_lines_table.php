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
        Schema::create('acquisition_lines', function (Blueprint $table) {
            $table->id();
            $table->float('unit_price')->nullable();

            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('acquisition_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquisition_lines');
    }
};
