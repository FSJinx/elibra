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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('website')->nullable();
            $table->time('opening_hour')->nullable();
            $table->time('closing_hour')->nullable();

            $table->string('heading')->nullable(); // This is what the library will show in the documents' header
            
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('logo_id')->nullable();
            $table->unsignedBigInteger('branch_head_id')->nullable();
            $table->unsignedBigInteger('campus_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
};
