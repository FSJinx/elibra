<?php

namespace App\Services;

use App\Models\Accession;
use App\Models\AcquisitionLines;
use App\Models\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AccessionService
{
    public function create(array $data): Accession
    {
        $accession = DB::transaction(function () use ($data) {
            return Accession::create(
                Arr::only($data, [
                    'accession_number',
                    'status',
                    'remarks',

                    'item_id',
                    'section_id',
                    'acquisition_line_id',
                ])
            );

        });

        CacheService::invalidate(CacheService::ACCESSION);

        return $accession;
    }

    public function update(Accession $accession, array $data): Accession
    {
        $accession = DB::transaction(function () use ($accession, $data) {
            $accession->update(
                Arr::only($data, [
                    'accession_number',
                    'status',
                    'remarks',

                    'item_id',
                    'section_id',
                    'acquisition_line_id',
                ])
            );

            return $accession->fresh();
        });

        CacheService::invalidate(CacheService::ACCESSION);

        return $accession;
    }

    public function delete(Accession $accession): void
    {
        DB::transaction(function () use ($accession) {
            $accession->delete();
        });

        CacheService::invalidate(CacheService::ACCESSION);
    }

    public function generateCopies(?int $quantity, Item $item, ?AcquisitionLines $acquisition_line)
    {
        $prefix = $this->getAccessionPrefix($item);

        foreach ($this->generateAccessionNumbers($prefix, $quantity) as $accessionNumber) {
            Accession::create([
                'accession_number' => $accessionNumber,
                'status' => 'available',
                'item_id' => $acquisition_line->item_id,
                'section_id' => $data['section_id'] ?? null,
                'acquisition_line_id' => $acquisition_line->id,
            ]);
        }
        // for ($i = 0; $i < $quantity; $i++) {
        //     Accession::create([
        //         'item_id' => $item->id,
        //         'acquisition_line_id' => $acquisition_line->id ?? null,
        //     ]);
        // }
    }

    private function getAccessionPrefix(Item $item): string
    {
        if (! $item->itemTypeCategory) {

            throw new RuntimeException(
                'The selected item does not have an item type category.'
            );
        }

        $categoryCode = strtoupper(
            trim($item->itemTypeCategory->code)
        );

        if ($categoryCode === '') {

            throw new RuntimeException(
                'The item type category does not have a valid code.'
            );
        }

        $branchCode = str_pad(
            (string) $item->branch_id,
            3,
            '0',
            STR_PAD_LEFT
        );

        return $categoryCode.$branchCode;
    }

    private function generateAccessionNumbers(string $prefix, int $quantity): array
    {

        if ($quantity <= 0) {
            return [];
        }

        /*
         * Get the highest sequence currently stored.
         *
         * We only consider accession numbers that exactly
         * match:
         *
         * PREFIX + numbers
         *
         * Example:
         *
         * GC0012330
         *
         * These will match.
         *
         * GC-001-2330
         * OLD-GC0012330
         * GC001ABC
         *
         * These will NOT match.
         */
        $lastAccession = Accession::where(
            'accession_number',
            'like',
            $prefix.'%'
        )
            ->whereRaw(
                'accession_number REGEXP ?',
                [
                    '^'.
                    preg_quote($prefix, '/').
                    '[0-9]+$',
                ]
            )
            ->orderByRaw(
                'CAST(SUBSTRING(accession_number, ?) AS UNSIGNED) DESC',
                [
                    strlen($prefix) + 1,
                ]
            )
            ->first();

        /*
         * Start at 1 if there are no existing
         * new-format accession numbers.
         */
        $nextSequence = 1;

        if ($lastAccession) {

            $lastSequence = substr(
                $lastAccession->accession_number,
                strlen($prefix)
            );

            $nextSequence =
                ((int) $lastSequence) + 1;
        }

        /*
         * Generate requested quantity.
         */
        $accessionNumbers = [];

        for ($i = 0; $i < $quantity; $i++) {

            $sequence =
                $nextSequence + $i;

            /*
             * Minimum 3 digits.
             *
             * 1    -> 001
             * 12   -> 012
             * 123  -> 123
             * 1230 -> 1230
             * 1231 -> 1231
             */
            $sequenceString = str_pad(
                (string) $sequence,
                3,
                '0',
                STR_PAD_LEFT
            );

            $accessionNumbers[] =
                $prefix.$sequenceString;
        }

        return $accessionNumbers;
    }
}
