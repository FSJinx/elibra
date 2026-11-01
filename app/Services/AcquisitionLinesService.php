<?php

namespace App\Services;

use App\Models\Accession;
use App\Models\Acquisition;
use App\Models\AcquisitionLines;
use App\Models\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AcquisitionLinesService
{
    public function __construct(
        private AccessionService $accessionService
    ) {}

    protected function fields(): array
    {
        return (new AcquisitionLines)->getFillable();
    }

    /**
     * Create acquisition line and its accessions.
     */
    public function create(array $data): AcquisitionLines
    {
        $acquisitionLine = DB::transaction(function () use ($data) {
            /*
             * Create acquisition line.
             */
            $acquisitionLine = AcquisitionLines::create(
                Arr::only($data, [
                    'unit_price',
                    'item_id',
                    'acquisition_id',
                ])
            );

            $quantity = (int) $data['quantity'];
            
            for ($i=0; $i < $quantity; $i++) { 
                $this->accessionService->create([
                    'item_id' => $data['item_id'],
                    'acquisition_line_id' => $acquisitionLine->id
                ]);
            }

            return $acquisitionLine->fresh([
                'item',
                'acquisition',
                'accessions',
            ]);
        });

        CacheService::invalidate(CacheService::ACQUISITION_LINES);

        return $acquisitionLine;
    }

    /**
     * Update acquisition line and synchronize its accessions.
     */
    public function update(AcquisitionLines $acquisitionLine, array $data): AcquisitionLines
    {

        $acquisitionLine = DB::transaction(function () use ($acquisitionLine, $data) {

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

                $additionalQuantity = $newQuantity - $oldQuantity;

                /*
                 * Get prefix.
                */
                // $prefix = $this->getAccessionPrefix($item);

                /*
                 * Generate additional accession numbers.
                 */
                // $accessionNumbers = $this->generateAccessionNumbers($prefix, $additionalQuantity);

                /*
                 * Create additional accessions.
                 */
                // foreach ($accessionNumbers as $accessionNumber) {

                //     Accession::create([
                //         'accession_number' => $accessionNumber,
                //         'status' => 'available',
                //         'item_id' => $acquisitionLine->item_id,
                //         'section_id' => $data['section_id'],
                //         'acquisition_line_id' => $acquisitionLine->id,
                //     ]);
                // }
            }

            /*
             * ==========================================
             * QUANTITY DECREASED
             * ==========================================
             */
            if ($newQuantity < $oldQuantity) {

                $removeQuantity = $oldQuantity - $newQuantity;

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
}
