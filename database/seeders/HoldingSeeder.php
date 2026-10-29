<?php

namespace Database\Seeders;

use App\Models\Accession;
use App\Models\Holding;
use App\Models\Librarian;
use App\Models\LoanMode;
use App\Models\Patron;
use App\Models\PatronTypeLoanPolicy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HoldingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $targetCount = 10;
            $existingCount = Holding::query()->count();
            $remaining = $targetCount - $existingCount;

            if ($remaining <= 0) {
                return;
            }

            $patron = Patron::query()->orderBy('id')->first();
            if (! $patron) {
                throw new RuntimeException('Seed patrons before creating test reservations.');
            }

            $librarianLibraryId = Librarian::query()
                ->whereNotNull('library_id')
                ->orderBy('id')
                ->value('library_id');

            $accessions = Accession::query()
                ->where('status', 'available')
                ->whereHas('item', fn ($query) => $query->when(
                    $librarianLibraryId,
                    fn ($libraryQuery) => $libraryQuery->where('library_id', $librarianLibraryId)
                ))
                ->orderBy('id')
                ->limit($remaining)
                ->lockForUpdate()
                ->get();

            if ($accessions->count() < $remaining) {
                throw new RuntimeException(
                    "Unable to seed {$remaining} test reservations. Ensure the librarian's library has at least {$remaining} available accessions."
                );
            }

            $loanMode = LoanMode::query()->orderBy('id')->first();
            if (! $loanMode) {
                throw new RuntimeException('Seed loan modes before creating test reservations.');
            }

            PatronTypeLoanPolicy::query()->firstOrCreate(
                [
                    'patron_type_id' => $patron->patron_type_id,
                    'loan_mode_id' => $loanMode->id,
                ],
                [
                    'loan_period_days' => 14,
                    'max_items' => 10,
                    'max_renewals' => 1,
                    'fine_per_due' => 0,
                    'grace_period' => 0,
                    'notes' => 'Default policy for seeded reservation testing.',
                ]
            );

            foreach ($accessions as $index => $accession) {
                $accession->update(['status' => 'reserved']);

                Holding::create([
                    'reserved_by' => $patron->id,
                    'accession_id' => $accession->id,
                    'pickup_date' => Carbon::now()->addDays(($index % 7) + 1),
                ]);
            }
        });
    }
}
