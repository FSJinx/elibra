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
            $table->foreign('logo_id')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('branch_head_id')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });

        // Sections Up
        Schema::table('branch_sections', function (Blueprint $table) {
            $table->foreign('section_head_id')->references('id')->on('librarians')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
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
            $table->foreign('profile_picture_id')->references('id')->on('media')->onDelete('cascade');
            $table->foreign('campus_id')->references('id')->on('campus')->onDelete('cascade');
        });

        // Librarians Up
        Schema::table('librarians', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Patrons Up
        Schema::table('patrons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('patron_type_id')->references('id')->on('patron_types')->onDelete('cascade');
        });

        // Subscriptions Up
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('thumbnail_id')->references('id')->on('media')->onDelete('cascade');
        });

        // Subscription Credentials Up
        Schema::table('subscription_credentials', function (Blueprint $table) {
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
        });

        // Items Up
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // Academics Up
        Schema::table('academics', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // User Permission
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // User Permissions Down
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['permission_id']);
        });

        // Academics Down
        Schema::table('academics', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['department_id']);
        });
        // if (Schema::hasTable('academics')) { Schema::table('academics', function (Blueprint $table) { $table->dropForeign(['item_id']); $table->dropForeign(['department_id']); }); }

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        // Subscriptions Down
        Schema::table('subscription_credentials', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropForeign(['campus_id']);
        });

        // Subscriptions Down
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_id']);
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
        });

        // Users Down
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
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
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['section_id']);
        });

        // Branches Down
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['branch_head_id']);
            $table->dropForeign(['logo_id']);
        });

    }
};
