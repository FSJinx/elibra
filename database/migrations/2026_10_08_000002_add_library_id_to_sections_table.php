<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('sections', 'library_id')) {
            return;
        }

        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('library_id')
                ->nullable()
                ->constrained('libraries')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('sections', 'library_id')) {
            return;
        }

        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['library_id']);
        });
    }
};
