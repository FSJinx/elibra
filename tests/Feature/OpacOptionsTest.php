<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OpacOptionsTest extends TestCase
{
    public function test_public_library_options_include_campus_for_branch_filtering(): void
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('campus_id');
            $table->softDeletes();
        });
        DB::table('libraries')->insert([
            ['id' => 1, 'name' => 'Main library', 'campus_id' => 10, 'deleted_at' => null],
            ['id' => 2, 'name' => 'Branch library', 'campus_id' => 20, 'deleted_at' => null],
            ['id' => 3, 'name' => 'Closed library', 'campus_id' => 10, 'deleted_at' => now()],
        ]);

        $this->getJson('/api/option/libraries')->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => 1, 'name' => 'Main library', 'campus_id' => 10])
            ->assertJsonFragment(['id' => 2, 'name' => 'Branch library', 'campus_id' => 20]);
    }
}
