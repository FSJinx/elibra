<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\HoldingSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use Tymon\JWTAuth\JWTGuard;
use App\Http\Middleware\RoleMiddleware;

class HoldingActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([JWTGuard::class, Authenticate::class, RoleMiddleware::class]);

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->softDeletes();
        });
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campus_id');
            $table->softDeletes();
        });
        Schema::create('librarians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('library_id');
            $table->softDeletes();
        });
        Schema::create('patron_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes();
        });
        Schema::create('patrons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('patron_type_id');
            $table->softDeletes();
        });
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('library_id');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('accessions', function (Blueprint $table) {
            $table->id();
            $table->string('accession_number');
            $table->string('status');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();
        });
        Schema::create('loan_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('patron_type_loan_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_period_days');
            $table->integer('max_renewals');
            $table->unsignedBigInteger('patron_type_id');
            $table->unsignedBigInteger('loan_mode_id');
        });
        Schema::create('holdings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserved_by');
            $table->unsignedBigInteger('accession_id');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->dateTime('pickup_date');
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamps();
        });
        Schema::create('circulations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('borrowed_at');
            $table->dateTime('due_at');
            $table->dateTime('returned_at')->nullable();
            $table->string('status');
            $table->integer('renewal_count')->default(0);
            $table->decimal('fine_charged')->default(0);
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->unsignedBigInteger('accession_id');
            $table->unsignedBigInteger('patron_id');
            $table->unsignedBigInteger('loan_mode_id');
            $table->unsignedBigInteger('return_received_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('users')->insert([
            ['id' => 1, 'role' => 'librarian', 'first_name' => 'Library', 'last_name' => 'Staff'],
            ['id' => 2, 'role' => 'patron', 'first_name' => 'Test', 'last_name' => 'Patron'],
        ]);
        DB::table('libraries')->insert(['id' => 1, 'campus_id' => 10]);
        DB::table('librarians')->insert(['id' => 1, 'user_id' => 1, 'library_id' => 1]);
        DB::table('patron_types')->insert(['id' => 1, 'name' => 'Student']);
        DB::table('patrons')->insert(['id' => 1, 'user_id' => 2, 'patron_type_id' => 1]);
        DB::table('items')->insert(['id' => 1, 'title' => 'Test Book', 'library_id' => 1]);
        DB::table('accessions')->insert(['id' => 1, 'accession_number' => 'ACC-1', 'status' => 'reserved', 'item_id' => 1]);
        DB::table('loan_modes')->insert(['id' => 1, 'name' => 'Take Home']);
        DB::table('patron_type_loan_policies')->insert([
            'id' => 1,
            'loan_period_days' => 14,
            'max_renewals' => 1,
            'patron_type_id' => 1,
            'loan_mode_id' => 1,
        ]);
        DB::table('holdings')->insert([
            'id' => 1,
            'reserved_by' => 1,
            'accession_id' => 1,
            'pickup_date' => '2026-10-30 09:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs((new User(['role' => 'librarian']))->forceFill(['id' => 1]), 'api');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_complete_reservation_creates_loan_and_marks_pickup_complete(): void
    {
        Carbon::setTestNow('2026-10-29 10:00:00');

        $this->postJson('/api/holding/1/complete', ['loan_mode_id' => 1])
            ->assertOk()
            ->assertJsonPath('data.status', 'borrowed')
            ->assertJsonPath('data.patron_id', 1)
            ->assertJsonPath('data.due_at', '2026-11-12T02:00:00.000000Z');

        $this->assertNotNull(DB::table('holdings')->where('id', 1)->value('picked_up_at'));
        $this->assertSame('on_load', DB::table('accessions')->where('id', 1)->value('status'));
    }

    public function test_holding_list_includes_reservation_and_item_details(): void
    {
        $this->getJson('/api/holding')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', 1)
            ->assertJsonPath('data.data.0.accession.accession_number', 'ACC-1')
            ->assertJsonPath('data.data.0.accession.item.title', 'Test Book')
            ->assertJsonPath('data.data.0.reserver.user.first_name', 'Test');
    }

    public function test_holding_seeder_creates_ten_reservations_idempotently(): void
    {
        foreach (range(2, 11) as $id) {
            DB::table('accessions')->insert([
                'id' => $id,
                'accession_number' => "ACC-{$id}",
                'status' => 'available',
                'item_id' => 1,
            ]);
        }

        $seeder = app(HoldingSeeder::class);
        $seeder->run();
        $seeder->run();

        $this->assertSame(10, DB::table('holdings')->count());
        $this->assertSame(10, DB::table('accessions')->where('status', 'reserved')->count());
    }

    public function test_delete_reservation_releases_item_if_still_reserved(): void
    {
        $this->deleteJson('/api/holding/1')
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('holdings', ['id' => 1]);
        $this->assertSame('available', DB::table('accessions')->where('id', 1)->value('status'));
    }
}
