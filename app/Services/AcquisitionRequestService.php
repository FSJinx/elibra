<?php

namespace App\Services;

use App\Models\AcquisitionRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AcquisitionRequestService
{
    /**
     * Create a new acquisition request.
     */
    public function create(
        array $data,
        int $userId
    ): AcquisitionRequest {
        return DB::transaction(function () use ($data, $userId) {

            $quantity = $data['quantity'] ?? 1;

            $unitPrice = $data['estimated_unit_price'] ?? null;

            $acquisitionRequest = AcquisitionRequest::create([
                ...Arr::only($data, [
                    'item_type_id',
                    'title',
                    'author',
                    'isbn',
                    'publisher',
                    'publication_year',
                    'edition',
                    'subject',
                    'justification',
                    'priority',
                    'estimated_unit_price',
                    'preferred_supplier',
                    'remarks',
                ]),

                // Automatically get the authenticated user's ID.
                'requested_by' => $userId,

                // Automatically calculate total price.
                'estimated_total_price' => $unitPrice !== null
                    ? $unitPrice * $quantity
                    : null,

                // Every new request starts as pending.
                'status' => 'pending',

                'quantity' => $quantity,
            ]);

            return $acquisitionRequest->fresh();
        });
    }

    /**
     * Update an existing acquisition request.
     */
    public function update(
        AcquisitionRequest $acquisitionRequest,
        array $data
    ): AcquisitionRequest {
        return DB::transaction(function () use (
            $acquisitionRequest,
            $data
        ) {

            /*
             * If quantity is included in the update,
             * use the new quantity.
             *
             * Otherwise, use the existing quantity.
             */
            $quantity = $data['quantity']
                ?? $acquisitionRequest->quantity;

            /*
             * If estimated_unit_price is included,
             * use the new price.
             *
             * Otherwise, use the existing price.
             */
            $unitPrice = array_key_exists(
                'estimated_unit_price',
                $data
            )
                ? $data['estimated_unit_price']
                : $acquisitionRequest->estimated_unit_price;

            /*
             * Only allow fields that can be changed
             * through the normal update endpoint.
             */
            $updateData = Arr::only($data, [
                'item_type_id',
                'title',
                'author',
                'isbn',
                'publisher',
                'publication_year',
                'edition',
                'subject',
                'quantity',
                'justification',
                'priority',
                'estimated_unit_price',
                'preferred_supplier',
                'remarks',
            ]);

            /*
             * Always recalculate total price.
             */
            $updateData['estimated_total_price'] = $unitPrice !== null
                ? $unitPrice * $quantity
                : null;

            $acquisitionRequest->update($updateData);

            return $acquisitionRequest->fresh();
        });
    }

    /**
     * Delete an acquisition request.
     */
    public function delete(
        AcquisitionRequest $acquisitionRequest
    ): bool {
        return DB::transaction(function () use (
            $acquisitionRequest
        ) {
            return $acquisitionRequest->delete();
        });
    }
}