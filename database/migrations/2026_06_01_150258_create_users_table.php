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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('last_name')->nullable();
            $table->string('first_name');
            $table->string('middle_initial', 5)->nullable();

            $table->enum('sex', ['male', 'female']);

            $table->date('birthdate')->nullable();

            $table->string('contact_number')->nullable();

            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('username')->nullable()->unique();
            $table->string('password');

            $table->enum('role', ['admin', 'librarian', 'patron'])->default('admin');
            $table->uuid('code')->unique()->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'expired'])->default('active');

            $table->unsignedTinyInteger('login_attempts')->default(0);

            $table->unsignedBigInteger('profile_picture_id')->nullable();
            $table->unsignedBigInteger('campus_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
