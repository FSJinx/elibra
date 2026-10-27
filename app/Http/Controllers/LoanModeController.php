<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanModeRequest;
use App\Http\Requests\UpdateLoanModeRequest;
use App\Models\LoanMode;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class LoanModeController extends Controller
{
    public function __construct(
        protected LoanMode $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        return CacheService::remember(
            CacheService::LOAN_MODES,
            $filters,
            now()->addHour(),
            function () {
                $modes = LoanMode::query()->paginate();

                return $this->response(
                    message: $modes->isEmpty() ? 'No loan modes found' : 'Loan modes successfully fetched',
                    data: $modes
                );
            }
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanModeRequest $request)
    {
        $data = $request->validated();
        $mode = $this->service->create($data);
        CacheService::invalidate(CacheService::LOAN_MODES);

        return $this->response(
            'success',
            'Loan mode added succesfully.',
            $mode
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanMode $loanMode)
    {
        return $this->response(
            'success',
            'Loan mode retrieved successfully.',
            $loanMode->toArray(),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UpdateLoanModeRequest $request, LoanMode $loanMode)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanModeRequest $request, LoanMode $loanMode)
    {
        $data = $request->validated();

        $loanMode->update($data);
        CacheService::invalidate(CacheService::LOAN_MODES);

        return $this->response(
            'success',
            'Loan mode updated succesfully',
            $loanMode->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanMode $loanMode)
    {
        $this->authorize('delete', $loanMode);

        if ($loanMode->circulations()->exists()) {
            return $this->response(
                'error',
                'This loan mode cannot be deleted because it is used by existing circulation records.',
                null,
                409
            );
        }

        if (! $loanMode->delete()) {
            return $this->response(
                'error',
                'The loan mode could not be deleted.',
                null,
                500
            );
        }

        CacheService::invalidate(CacheService::LOAN_MODES);

        return $this->response(
            'success',
            'Loan mode deleted succesfully',
            null,
            200
        );
    }
}
