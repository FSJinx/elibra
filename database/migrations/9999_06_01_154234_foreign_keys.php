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
        // Branches Up
        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });

        // Sections Up
        Schema::table('branch_sections', function (Blueprint $table) {
            $table->foreign('section_head_id')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Departments Up
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });

        // Programs Up
        Schema::table('programs', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // Users Up
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('profile_picture_id')->references('id')->on('profile_photos')->onDelete('cascade');
        });

        // Librarians Up
        Schema::table('librarians', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('primary_role_id')->references('id')->on('library_roles')->onDelete('cascade');
        });

        // Patrons Up
        Schema::table('patrons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('patron_type_id')->references('id')->on('patron_types')->onDelete('cascade');
        });

        // Librarian Secondary Roles Up
        Schema::table('librarian_secondary_roles', function (Blueprint $table) {
            $table->foreign('librarian_id')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('library_role_id')->references('id')->on('library_roles')->onDelete('cascade');
        });

        // Profile Pictures Up
        Schema::table('profile_photos', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Profile Pictures Down
        Schema::table('profile_photos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Librarian Secondary Roles Down
        Schema::table('librarian_secondary_roles', function (Blueprint $table) {
            $table->dropForeign(['librarian_id']);
            $table->dropForeign(['library_role_id']);
        });

        // Patrons Down
        Schema::table('patrons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['program_id']);
            $table->dropForeign(['patron_type_id']);
        });

        // Librarians Down
        Schema::table('librarians', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['primary_role_id']);
        });

        // Users Down
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_picture_id']);
        });

        // Programs Down
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        // Departments Down
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
        });

        // Sections Down
        Schema::table('branch_sections', function (Blueprint $table) {
            $table->dropForeign(['section_head_id']);
            $table->dropForeign(['branch_id']);
        });

        // Branches Down
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
        });
    }
};
