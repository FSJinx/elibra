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
        Schema::create('patron_type_loan_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_period_days')->default(1); // in days

            $table->integer('max_items')->default(0); 
            $table->integer('max_renewals')->default(0); 
            $table->decimal('fine_per_due', 10, 2)->default(0);
            $table->integer('grace_period')->default(0); // in hours

            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('patron_type_id');  
            $table->unsignedBigInteger('loan_mode_id');
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('patron_type_loan_policies');
    }
};
