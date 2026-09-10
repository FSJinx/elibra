<?php

namespace App\Services;

use App\Models\Accession;
use App\Models\AcquisitionLines;
use App\Models\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AcquisitionLinesService
{
    /**
     * Create acquisition line and its accessions.
     */
    public function create(array $data): AcquisitionLines
    {
        $acquisitionLine = DB::transaction(function () use ($data) {

            $quantity = isset($data['quantity']) ? (float) $data['quantity'] : null;
            $unitPrice = isset($data['unit_price']) ? (float) $data['unit_price'] : null;
            $discount = isset($data['discount']) ? (float) $data['discount'] : 0.0;

            $data['net_price'] = $this->calculateNetPrice($quantity, $unitPrice, $discount);

            /*
             * Get item and category.
             */
            $item = Item::with('itemTypeCategory')
                ->findOrFail($data['item_id']);

            /*
             * Example:
             *
             * Category = GC
             * Branch   = 1
             *
             * Prefix = GC001
             */
            $prefix = $this->getAccessionPrefix($item);

            /*
             * Create acquisition line.
             */
            $acquisitionLine = AcquisitionLines::create(
                Arr::only($data, [
                    'quantity',
                    'unit_price',
                    'discount',
                    'net_price',
                    'item_id',
                    'acquisition_id',
                ])
            );

            /*
             * Generate accession numbers.
             */
            $accessionNumbers = $this->generateAccessionNumbers(
                $prefix,
                $acquisitionLine->quantity
            );

            /*
             * Create accessions.
             */
            foreach ($accessionNumbers as $accessionNumber) {

                Accession::create([
                    'accession_number' => $accessionNumber,
                    'status' => 'available',
                    'item_id' => $acquisitionLine->item_id,
                    'section_id' => $data['section_id'],
                    'acquisition_line_id' => $acquisitionLine->id,
                ]);
            }

            return $acquisitionLine->fresh([
                'item',
                'acquisition',
                'accessions',
            ]);
        }, 5);

        CacheService::invalidate(
            CacheService::ACQUISITION_LINES
        );

        return $acquisitionLine;
    }


    /**
     * Update acquisition line and synchronize its accessions.
     */
    public function update(
        AcquisitionLines $acquisitionLine,
        array $data
    ): AcquisitionLines {

        $acquisitionLine = DB::transaction(function () use (
            $acquisitionLine,
            $data
        ) {

            $quantity = array_key_exists('quantity', $data) ? (float) $data['quantity'] : $acquisitionLine->quantity;
            $unitPrice = array_key_exists('unit_price', $data) ? (float) $data['unit_price'] : $acquisitionLine->unit_price;
            $discount = array_key_exists('discount', $data) ? (float) $data['discount'] : ($acquisitionLine->discount ?? 0.0);

            $data['net_price'] = $this->calculateNetPrice($quantity, $unitPrice, $discount);

            $oldQuantity = (int) $acquisitionLine->quantity;
            $newQuantity = array_key_exists('quantity', $data)
                ? (int) $data['quantity']
                : $oldQuantity;

            /*
             * Get new item.
             */
            $item = Item::with('itemTypeCategory')
                ->findOrFail($data['item_id'] ?? $acquisitionLine->item_id);

            /*
             * Update acquisition line.
             */
            $acquisitionLine->update(
                Arr::only($data, [
                    'quantity',
                    'unit_price',
                    'discount',
                    'net_price',
                    'item_id',
                    'acquisition_id',
                ])
            );

            /*
             * Update existing accessions.
             *
             * We intentionally DO NOT change the
             * accession_number.
             */
            Accession::where(
                'acquisition_line_id',
                $acquisitionLine->id
            )->update([
                'item_id' => $data['item_id'],
                'section_id' => $data['section_id'],
            ]);


            /*
             * ==========================================
             * QUANTITY INCREASED
             * ==========================================
             */
            if ($newQuantity > $oldQuantity) {

                $additionalQuantity =
                    $newQuantity - $oldQuantity;

                /*
                 * Get prefix.
                 */
                $prefix = $this->getAccessionPrefix($item);

                /*
                 * Generate additional accession numbers.
                 */
                $accessionNumbers =
                    $this->generateAccessionNumbers(
                        $prefix,
                        $additionalQuantity
                    );

                /*
                 * Create additional accessions.
                 */
                foreach ($accessionNumbers as $accessionNumber) {

                    Accession::create([
                        'accession_number' => $accessionNumber,
                        'status' => 'available',
                        'item_id' => $acquisitionLine->item_id,
                        'section_id' => $data['section_id'],
                        'acquisition_line_id' => $acquisitionLine->id,
                    ]);
                }
            }


            /*
             * ==========================================
             * QUANTITY DECREASED
             * ==========================================
             */
            if ($newQuantity < $oldQuantity) {

                $removeQuantity =
                    $oldQuantity - $newQuantity;

                /*
                 * Only remove available accessions.
                 */
                $accessions = Accession::where(
                    'acquisition_line_id',
                    $acquisitionLine->id
                )
                    ->where('status', 'available')
                    ->latest('id')
                    ->limit($removeQuantity)
                    ->get();

                /*
                 * Cannot reduce quantity if some
                 * accessions are already being used.
                 */
                if ($accessions->count() < $removeQuantity) {

                    throw new RuntimeException(
                        'The quantity cannot be reduced because some accessions are already in use.'
                    );
                }

                foreach ($accessions as $accession) {
                    $accession->delete();
                }
            }

            return $acquisitionLine->fresh([
                'item',
                'acquisition',
                'accessions',
            ]);

        }, 5);

        CacheService::invalidate(
            CacheService::ACQUISITION_LINES
        );

        return $acquisitionLine;
    }


    /**
     * Delete acquisition line and its accessions.
     */
    public function delete(
        AcquisitionLines $acquisitionLine
    ): bool {

        $deleted = DB::transaction(function () use (
            $acquisitionLine
        ) {

            /*
             * Check if any accession is already used.
             */
            $hasUsedAccessions = Accession::where(
                'acquisition_line_id',
                $acquisitionLine->id
            )
                ->whereNotIn('status', ['available'])
                ->exists();

            if ($hasUsedAccessions) {

                throw new RuntimeException(
                    'This acquisition line cannot be deleted because one or more accessions are already in use.'
                );
            }

            /*
             * Delete related accessions.
             */
            Accession::where(
                'acquisition_line_id',
                $acquisitionLine->id
            )->delete();

            /*
             * Delete acquisition line.
             */
            $acquisitionLine->delete();

            return true;
        }, 5);

        if ($deleted) {

            CacheService::invalidate(
                CacheService::ACQUISITION_LINES
            );
        }

        return $deleted;
    }


    private function calculateNetPrice($quantity, $unitPrice, $discount = 0.0): ?float
    {
        if ($quantity === null || $unitPrice === null) {
            return null;
        }

        $grossTotal = (float) $quantity * (float) $unitPrice;
        $discountAmount = $discount === null ? 0.0 : (float) $discount;

        return $grossTotal - $discountAmount;
    }

    /**
     * Get accession prefix.
     *
     * Example:
     *
     * Category = GC
     * Branch   = 1
     *
     * Result = GC001
     */
    private function getAccessionPrefix(Item $item): string
    {
        if (!$item->itemTypeCategory) {

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

        return $categoryCode . $branchCode;
    }


    /**
     * Generate accession numbers.
     *
     * This method:
     *
     * 1. Looks only at the new accession format.
     * 2. Ignores old/different formats.
     * 3. Finds the highest numeric sequence.
     * 4. Generates the next numbers.
     *
     * Example:
     *
     * Existing:
     *
     * GC001001
     * GC001002
     * GC0012330
     *
     * Old formats:
     *
     * GC-001-001
     * OLD-GC-001
     *
     * Quantity = 3
     *
     * Result:
     *
     * GC0012331
     * GC0012332
     * GC0012333
     */
    private function generateAccessionNumbers(
        string $prefix,
        int $quantity
    ): array {

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
            $prefix . '%'
        )
            ->whereRaw(
                'accession_number REGEXP ?',
                [
                    '^' .
                    preg_quote($prefix, '/') .
                    '[0-9]+$'
                ]
            )
            ->orderByRaw(
                'CAST(SUBSTRING(accession_number, ?) AS UNSIGNED) DESC',
                [
                    strlen($prefix) + 1
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
                $prefix . $sequenceString;
        }

        return $accessionNumbers;
    }
}