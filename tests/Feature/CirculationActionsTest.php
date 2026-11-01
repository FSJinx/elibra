<?php

namespace Tests\Feature;

use App\Models\Circulation;
use App\Models\User;
use App\Services\CirculationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CirculationActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->softDeletes();
        });
        Schema::create('librarians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('library_id')->nullable();
            $table->softDeletes();
        });
        Schema::create('patron_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes();
        });
        Schema::create('patrons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('patron_type_id');
            $table->softDeletes();
        });
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('accessions', function (Blueprint $table) {
            $table->id();
            $table->string('accession_number');
            $table->string('status');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('loan_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('renewable')->default(false);
        });
        Schema::create('patron_type_loan_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_period_days');
            $table->integer('max_renewals');
            $table->unsignedBigInteger('patron_type_id');
            $table->unsignedBigInteger('loan_mode_id');
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
            ['id' => 1, 'first_name' => 'Librarian', 'last_name' => 'One', 'username' => 'librarian'],
            ['id' => 2, 'first_name' => 'Patron', 'last_name' => 'One', 'username' => 'patron'],
        ]);
        DB::table('librarians')->insert(['id' => 1, 'user_id' => 1]);
        DB::table('patron_types')->insert(['id' => 1, 'name' => 'Student']);
        DB::table('patrons')->insert(['id' => 1, 'user_id' => 2, 'patron_type_id' => 1]);
        DB::table('items')->insert(['id' => 1, 'title' => 'Test book']);
        DB::table('accessions')->insert(['id' => 1, 'accession_number' => 'ACC-1', 'status' => 'on_load', 'item_id' => 1]);
        DB::table('loan_modes')->insert(['id' => 1, 'name' => 'In House', 'renewable' => true]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_return_marks_loan_returned_and_makes_item_available(): void
    {
        Carbon::setTestNow('2026-10-29 10:00:00');
        $loanId = $this->createLoan();
        $librarian = User::findOrFail(1);

        $returned = app(CirculationService::class)->returnLoan(Circulation::findOrFail($loanId), $librarian);

        $this->assertSame('returned', $returned->status);
        $this->assertSame('2026-10-29 10:00:00', $returned->returned_at->format('Y-m-d H:i:s'));
        $this->assertSame(1, $returned->return_received_by);
        $this->assertSame('available', DB::table('accessions')->where('id', 1)->value('status'));
    }

    public function test_renewal_extends_due_date_by_policy_and_respects_maximum(): void
    {
        Carbon::setTestNow('2026-10-29 10:00:00');
        DB::table('patron_type_loan_policies')->insert([
            'loan_period_days' => 14,
            'max_renewals' => 1,
            'patron_type_id' => 1,
            'loan_mode_id' => 1,
        ]);
        $loanId = $this->createLoan(dueAt: '2026-10-31 10:00:00');
        $service = app(CirculationService::class);

        $renewed = $service->renew(Circulation::findOrFail($loanId));

        $this->assertSame('2026-11-14 10:00:00', $renewed->due_at->format('Y-m-d H:i:s'));
        $this->assertSame(1, $renewed->renewal_count);

        try {
            $service->renew($renewed);
            $this->fail('A loan at its renewal limit should not be renewable.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('renewal_count', $exception->errors());
        }
    }

    private function createLoan(string $dueAt = '2026-10-30 10:00:00'): int
    {
        return DB::table('circulations')->insertGetId([
            'borrowed_at' => '2026-10-28 10:00:00',
            'due_at' => $dueAt,
            'status' => 'borrowed',
            'renewal_count' => 0,
            'fine_charged' => 0,
            'accession_id' => 1,
            'patron_id' => 1,
            'loan_mode_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
