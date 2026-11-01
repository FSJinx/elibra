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
        Schema::create('aca_repos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['ut', 'gt']);
            $table->longText('description');
            $table->string('doi')->nullable();  
            $table->string('electronic_file');
            $table->json('keywords');
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('statusRemark')->nullable();
            $table->boolean('on_catalog')->default(false);
            
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('library_id');
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aca_repos');
    }
};
