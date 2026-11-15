<?php

namespace App\Services;

use App\Models\Accession;
use App\Models\Acquisition;
use App\Models\AcquisitionLines;
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

            for ($i = 0; $i < (int) $data['quantity' ?? 1]; $i++) {
                $this->accessionService->create([
                    'accession_number' => $data['accession_number'] ?? null,
                    'item_id' => $data['item_id'],
                    'acquisition_line_id' => $acquisitionLine->id,
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
            $oldQuantity = Accession::query()->where('acquisition_line_id', $acquisitionLine->id)->count();
            $newQuantity = $data['quantity'] ?? 0;

            $acquisitionLine->update(Arr::only($data, self::fields()));

            if ($newQuantity > $oldQuantity) {
                $this->accessionService->create([
                    'item_id' => $acquisitionLine->item_id,
                    'acquisition_line_id' => $acquisitionLine->id,
                    'quantity' => $newQuantity - $oldQuantity,
                ]);
            } elseif ($newQuantity < $oldQuantity) {
                $accessions = Accession::where('acquisition_line_id', $acquisitionLine->id)
                    ->where('status', 'available')
                    ->latest('id')
                    ->limit($oldQuantity - $newQuantity)
                    ->get();

                foreach ($accessions as $a) {
                    $this->accessionService->update($a, [
                        'status' => 'condemned',
                        'remarks' => 'Condemned from wrong quantity count in acquisition',
                    ]);
                    $a->delete();
                }
            }

            return $acquisitionLine->fresh(['item', 'acquisition', 'accessions']);

        }, 5);

        CacheService::invalidate(
            CacheService::ACQUISITION_LINES
        );

        return $acquisitionLine;
    }

    /**
     * Delete acquisition line and its accessions.
     */
    public function delete(AcquisitionLines $acquisitionLine): bool
    {

        $deleted = DB::transaction(function () use ($acquisitionLine) {

            /*
             * Check if any accession is already used.
             */
            $hasUsedAccessions = Accession::where('acquisition_line_id', $acquisitionLine->id)
                ->whereNotIn('status', ['available'])
                ->exists();

            if ($hasUsedAccessions) {

                throw new RuntimeException('This acquisition line cannot be deleted because one or more accessions are already in use.');
            }

            /*
             * Delete related accessions.
             */
            Accession::where('acquisition_line_id', $acquisitionLine->id)->delete();

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
