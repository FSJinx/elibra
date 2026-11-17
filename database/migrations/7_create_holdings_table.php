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
        Schema::create('holdings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserver_id');
            $table->unsignedBigInteger('accession_id');
            $table->unsignedBigInteger('processor_id')->nullable();

            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('pending');
            $table->string('status_remark')->nullable();

            $table->dateTime('expected_pickup_date');
            $table->dateTime('pickup_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holdings');
    }
};
