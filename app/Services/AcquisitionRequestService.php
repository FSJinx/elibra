<?php

namespace App\Services;

use App\Models\AcquisitionRequest;
use App\Models\User;
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
            $normalized = $this->normalizePayload($data, null, $userId);

            $quantity = $normalized['quantity'] ?? 1;
            $unitPrice = $normalized['estimated_unit_price'] ?? null;

            $acquisitionRequest = AcquisitionRequest::create([
                ...Arr::only($normalized, [
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
                    'closed_descriptions',
                    'reviewed_at',
                    'remarks',
                ]),

                'request_id' => Str::uuid()->toString(),
                'requested_by' => $userId,
                'request_status' => $normalized['request_status'] ?? 'pending',
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
        array $data,
        ?int $userId = null
    ): AcquisitionRequest {
        return DB::transaction(function () use ($acquisitionRequest, $data, $userId) {
            $normalized = $this->normalizePayload($data, $acquisitionRequest, $userId);

            $quantity = $normalized['quantity'] ?? $acquisitionRequest->quantity;
            $unitPrice = array_key_exists('estimated_unit_price', $normalized)
                ? $normalized['estimated_unit_price']
                : $acquisitionRequest->estimated_unit_price;

            $updateData = Arr::only($normalized, [
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
                'closed_descriptions',
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

    protected function normalizePayload(array $data, ?AcquisitionRequest $existing = null, ?int $userId = null): array
    {
        $normalized = $data;

        if (array_key_exists('closed_description', $normalized) && ! array_key_exists('closed_descriptions', $normalized)) {
            $normalized['closed_descriptions'] = $normalized['closed_description'];
        }

        unset($normalized['closed_description']);

        $procurementStatus = $normalized['procurement_status'] ?? $existing?->procurement_status;
        $isClosed = $normalized['is_closed'] ?? $existing?->is_closed ?? false;

        if (in_array($procurementStatus, ['ordered', 'received'], true)) {
            $normalized['is_closed'] = $existing?->is_closed ?? false;
        }

        $shouldClose = (bool) ($normalized['is_closed'] ?? $existing?->is_closed ?? false);

        if ($shouldClose) {
            $user = $userId ? User::find($userId) : null;
            $userName = $user ? trim(($user->first_name ?? '').' '.($user->last_name ?? '')) : 'System';
            $userName = $userName !== '' ? $userName : 'System';

            $userRole = $user && ! empty($user->role)
                ? str_replace('_', ' ', ucfirst($user->role))
                : 'System';

            $description = $normalized['closed_remarks'] ?? $existing?->closed_remarks ?? 'No additional description provided.';
            $normalized['closed_descriptions'] = 'This was closed by '.$userRole.' '.$userName.' and the description: '.$description;
        }

        return $normalized;
    }
}