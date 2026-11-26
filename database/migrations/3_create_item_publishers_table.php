<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_publishers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->year('year_published')->nullable();
            $table->timestamps();

            $table->unique(['item_id', 'publisher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_publishers');
    }
};
