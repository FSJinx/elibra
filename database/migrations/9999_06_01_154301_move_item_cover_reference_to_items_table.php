<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('cover_media_id')->nullable()->after('language_id');
            $table->foreign('cover_media_id')->references('id')->on('media')->nullOnDelete();
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn('item_id');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('id');
            $table->foreign('item_id')->references('id')->on('items')->cascadeOnDelete();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['cover_media_id']);
            $table->dropColumn('cover_media_id');
        });
    }
};