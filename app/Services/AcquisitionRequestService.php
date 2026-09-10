<?php

namespace App\Services;

use App\Models\AcquisitionRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                    'requested_by',
                    'item_type_id',
                    'reviewed_by',
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
                    'request_status',
                    'procurement_status',
                    'is_closed',
                    'closed_remarks',
                    'reviewed_at',
                    'remarks',
                ]),

                'request_id' => Str::uuid()->toString(),
                'requested_by' => $userId,
                'request_status' => $data['request_status'] ?? 'pending',
                'estimated_total_price' => $unitPrice !== null ? $unitPrice * $quantity : null,
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
        return DB::transaction(function () use ($acquisitionRequest, $data) {

            $quantity = $data['quantity'] ?? $acquisitionRequest->quantity;
            $unitPrice = array_key_exists('estimated_unit_price', $data)
                ? $data['estimated_unit_price']
                : $acquisitionRequest->estimated_unit_price;

            $updateData = Arr::only($data, [
                'request_id',
                'requested_by',
                'item_type_id',
                'reviewed_by',
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
                'request_status',
                'procurement_status',
                'is_closed',
                'closed_remarks',
                'reviewed_at',
                'remarks',
            ]);

            $updateData['estimated_total_price'] = $unitPrice !== null ? $unitPrice * $quantity : null;

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
        return DB::transaction(function () use ($acquisitionRequest) {
            return $acquisitionRequest->delete();
        });
    }
}