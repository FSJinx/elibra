<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinesTransactionRequest;
use App\Http\Requests\UpdateFinesTransactionRequest;
use App\Models\FinesTransaction;
use App\Services\FinesTransactionService;

class FinesTransactionController extends Controller
{
    public function __construct(
        protected FinesTransactionService $finesTransactionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = FinesTransaction::with([
            'patron',
            'circulation',
            'processedBy',
        ])->get();

        return $this->response(
            'success',
            'Fine transactions retrieved successfully.',
            $transactions->toArray(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinesTransactionRequest $request)
    {
        $transaction = $this->finesTransactionService->create($request->validated());

        return $this->response(
            'success',
            'Fine transaction created successfully.',
            $transaction->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(FinesTransaction $finesTransaction)
    {
        $finesTransaction->load([
            'patron',
            'circulation',
            'processedBy',
        ]);

        return $this->response(
            'success',
            'Fine transaction retrieved successfully.',
            $finesTransaction->toArray(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinesTransactionRequest $request, FinesTransaction $finesTransaction)
    {
        $updated = $this->finesTransactionService->update($finesTransaction, $request->validated());

        return $this->response(
            'success',
            'Fine transaction updated successfully.',
            $updated->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinesTransaction $finesTransaction)
    {
        $deleted = $this->finesTransactionService->delete($finesTransaction);

        if (! $deleted) {
            return $this->response(
                'error',
                'The selected fine transaction could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Fine transaction deleted successfully.',
            null,
            200
        );
    }
}
