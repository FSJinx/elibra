<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanModeRequest;
use App\Http\Requests\UpdateLoanModeRequest;
use App\Models\LoanMode;
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
        $modes = LoanMode::query()->paginate();

        return $this->response(
            message: $modes->isEmpty() ? 'No loan modes found' : 'Loan modes successfully fetched',
            data: $modes
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
        return $this->response(data: $loanMode);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanMode $loanMode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanModeRequest $request, LoanMode $loanMode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanMode $loanMode)
    {
        //
    }
}
