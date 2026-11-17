<?php

namespace Tests\Feature;

use App\Models\Accession;
use Database\Seeders\AccessionSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccessionSeederTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('item_type_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
        });
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_type_category_id');
            $table->unsignedBigInteger('library_id');
            $table->softDeletes();
        });
        Schema::create('accessions', function (Blueprint $table) {
            $table->id();
            $table->string('accession_number')->unique()->nullable();
            $table->string('status')->default('available');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('item_type_categories')->insert(['id' => 1, 'code' => 'gc']);
        DB::table('items')->insert([
            ['id' => 1, 'item_type_category_id' => 1, 'library_id' => 1],
            ['id' => 2, 'item_type_category_id' => 1, 'library_id' => 1],
        ]);
        DB::table('accessions')->insert([
            ['id' => 1, 'accession_number' => 'GC001010', 'status' => 'available', 'item_id' => 1],
            ['id' => 2, 'accession_number' => null, 'status' => 'available', 'item_id' => 1],
        ]);
    }

    public function test_it_numbers_existing_null_accessions_and_adds_a_copy_for_items_without_accessions(): void
    {
        $seeder = new AccessionSeeder;

        $seeder->run();

        $this->assertSame('GC001011', Accession::findOrFail(2)->accession_number);
        $this->assertSame('GC001012', Accession::where('item_id', 2)->value('accession_number'));
        $this->assertSame(3, Accession::count());

        $seeder->run();

        $this->assertSame(3, Accession::count());
        $this->assertSame(1, Accession::where('item_id', 2)->count());
    }
}
