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
        Schema::create('patron_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->nullable();
            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('validity_period'); // This is saved in years
            $table->boolean('has_campus')->default(false);
            $table->boolean('has_department')->default(false);
            $table->boolean('has_program')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patron_types');
    }
};
