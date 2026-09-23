<?php

namespace App\Http\Controllers;

use App\Models\PatronTypeLoanPolicy;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatronTypeLoanPolicyRequest;
use App\Http\Requests\UpdatePatronTypeLoanPolicyRequest;
use App\Services\PatronTypeLoanPolicyService;

class PatronTypeLoanPolicyController extends Controller
{
    protected PatronTypeLoanPolicyService $policyService;

    public function __construct(PatronTypeLoanPolicyService $policyService)
    {
        $this->policyService = $policyService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = PatronTypeLoanPolicy::with(['patronType', 'loanMode'])->get();

        return $this->response(
            'success',
            'Patron type loan policies retrieved successfully.',
            $policies->toArray(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatronTypeLoanPolicyRequest $request)
    {
        $policy = $this->policyService->create($request->validated());

        return $this->response(
            'success',
            'Policy Loan created successfully',
            $policy->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PatronTypeLoanPolicy $patronTypeLoanPolicy)
    {
        $policy = $patronTypeLoanPolicy->load(['patronType', 'loanMode']);

        return $this->response(
            'success',
            'Patron type loan policy retrieved successfully.',
            $policy->toArray(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatronTypeLoanPolicyRequest $request, PatronTypeLoanPolicy $patronTypeLoanPolicy)
    {
        $policy = $this->policyService->update(
            $patronTypeLoanPolicy,
            $request->validated()
        );

        return $this->response(
            'success',
            'Policy Loan updated successfully',
            $policy->toArray(),
            200
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatronTypeLoanPolicy $patronTypeLoanPolicy)
    {
        $this->authorize('delete', $patronTypeLoanPolicy);

        $deleted = $this->policyService->delete($patronTypeLoanPolicy);

        if(!$deleted) {
            return $this->response(
                'Error',
                'The selected policy loan record could not be deleted.',
                null, 500
            );
        }

        return $this->response(
            'success',
            'Policy Loan record deleted successfully',
            null,
            200
        );
    }
}
