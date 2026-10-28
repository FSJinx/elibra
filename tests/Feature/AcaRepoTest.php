<?php

namespace Tests\Feature;

use App\Models\AcaRepo;
use App\Models\Patron;
use App\Models\User;
use App\Services\AcaRepoService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use Tymon\JWTAuth\JWTGuard;

class AcaRepoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([JWTGuard::class, Authenticate::class]);
        (require database_path('migrations/3_create_aca_repos_table.php'))->up();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->softDeletes();
        });
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campus_id');
            $table->softDeletes();
        });
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
        });
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
        });
        DB::table('users')->insert(['id' => 1, 'role' => 'librarian', 'campus_id' => 5]);
        DB::table('libraries')->insert(['id' => 1, 'campus_id' => 5]);
        Storage::fake('public');
        $this->actingAs((new User(['role' => 'librarian']))->forceFill(['id' => 1]), 'api');
    }

    public function test_staff_can_create_read_update_and_delete_a_submission(): void
    {
        $payload = [
            'title' => 'Research submission', 'category' => 'ut',
            'description' => 'A research abstract.', 'keywords' => ['research', 'library'],
            'status' => 'pending', 'on_catalog' => false,
            'language_id' => null, 'program_id' => null,
            'library_id' => 1,
        ];
        $response = $this->post('/api/acarepo', [
            ...$payload,
            'electronic_file' => UploadedFile::fake()->create('manuscript.pdf', 100, 'application/pdf'),
        ])->assertCreated()
            ->assertJsonPath('data.keywords', ['research', 'library'])
            ->assertJsonPath('data.on_catalog', false)
            ->assertJsonPath('data.submitted_by', 1);
        $id = $response->json('data.id');
        Storage::disk('public')->assertExists($response->json('data.electronic_file'));

        $this->getJson('/api/acarepo')->assertOk()->assertJsonPath('data.0.id', $id);
        $this->getJson("/api/acarepo/$id")->assertOk()->assertJsonPath('data.title', $payload['title']);
        $this->putJson("/api/acarepo/$id", [...$payload, 'status' => 'approved', 'on_catalog' => true])
            ->assertOk()->assertJsonPath('data.status', 'approved')->assertJsonPath('data.on_catalog', true);
        $this->deleteJson("/api/acarepo/$id")->assertOk();
        $this->getJson("/api/acarepo/$id")->assertNotFound();
    }

    public function test_invalid_submission_is_rejected(): void
    {
        $this->postJson('/api/acarepo', [
            'category' => 'invalid', 'keywords' => 'invalid', 'status' => 'invalid', 'on_catalog' => 'invalid',
        ])->assertUnprocessable()->assertJsonValidationErrors(['title', 'description', 'category', 'keywords', 'status', 'on_catalog', 'electronic_file', 'library_id']);
    }

    public function test_patron_submission_service_forces_pending_and_off_catalog(): void
    {
        $record = app(AcaRepoService::class)->submitAcaRepo([
            'title' => 'Patron research', 'category' => 'gt',
            'description' => 'Abstract', 'keywords' => ['research'],
            'status' => 'approved', 'on_catalog' => true,
            'electronic_file' => UploadedFile::fake()->create('manuscript.pdf', 100, 'application/pdf'),
            'library_id' => 1,
            'submitted_by' => 1,
        ]);

        $this->assertSame('pending', $record->fresh()->status);
        $this->assertFalse($record->fresh()->on_catalog);
    }

    public function test_patron_can_submit_manuscript_without_setting_review_fields(): void
    {
        (require database_path('migrations/2_create_patrons_table.php'))->up();
        DB::table('languages')->insert(['id' => 1]);
        DB::table('programs')->insert(['id' => 9]);
        DB::table('users')->insert(['id' => 7, 'role' => 'patron', 'campus_id' => 5]);
        DB::table('libraries')->insert(['id' => 2, 'campus_id' => 6]);
        $user = (new User(['role' => 'patron']))->forceFill(['id' => 7, 'campus_id' => 5]);
        Patron::create(['user_id' => 7, 'patron_type_id' => 1, 'program_id' => 9]);
        $this->actingAs($user, 'api');

        $this->post('/api/acarepo', [
            'title' => 'Wrong campus manuscript', 'category' => 'ut',
            'description' => 'Research abstract.', 'keywords' => ['research'],
            'language_id' => 1, 'library_id' => 2,
            'electronic_file' => UploadedFile::fake()->create('manuscript.pdf', 100, 'application/pdf'),
        ])->assertUnprocessable()->assertJsonValidationErrors(['library_id']);

        $this->post('/api/acarepo', [
            'title' => 'Patron manuscript', 'category' => 'ut',
            'description' => 'Research abstract.', 'keywords' => ['research'],
            'language_id' => 1, 'program_id' => 999,
            'status' => 'approved', 'on_catalog' => true,
            'library_id' => 1,
            'electronic_file' => UploadedFile::fake()->create('manuscript.pdf', 100, 'application/pdf'),
        ])->assertCreated()->assertJsonPath('data.program_id', 9)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.on_catalog', false)
            ->assertJsonPath('data.description', 'Research abstract.')
            ->assertJsonPath('data.submitted_by', 7);
    }

    public function test_patron_cannot_manage_submissions(): void
    {
        $record = AcaRepo::create([
            'title' => 'Protected submission', 'category' => 'ut',
            'description' => 'Abstract', 'keywords' => ['research'],
            'electronic_file' => 'aca-repo/files/manuscript.pdf',
            'library_id' => 1,
            'submitted_by' => 1,
        ]);
        $this->actingAs(new User(['role' => 'patron']), 'api');
        $this->getJson('/api/acarepo')->assertUnauthorized();
        $this->putJson("/api/acarepo/{$record->id}", [])->assertUnauthorized();
        $this->deleteJson("/api/acarepo/{$record->id}")->assertUnauthorized();
    }
}
