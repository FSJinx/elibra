<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('id');
            $table->foreign('item_id')->references('id')->on('items')->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE media MODIFY image_type ENUM('profile', 'logo', 'subscription', 'book_cover', 'item_cover', 'document', 'banner', 'other') NOT NULL");
    }

    public function down(): void
    {
        DB::table('media')->where('image_type', 'item_cover')->update(['image_type' => 'other']);

        DB::statement("ALTER TABLE media MODIFY image_type ENUM('profile', 'logo', 'subscription', 'book_cover', 'document', 'banner', 'other') NOT NULL");

        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn('item_id');
        });
    }
};
