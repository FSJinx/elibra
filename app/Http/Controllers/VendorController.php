<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Services\VendorService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected VendorService $vendorService;
    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = Vendor::all();

        return $this->response(
            'success',
            'Vendor retrieved successfully',
            $vendor->toArray(),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendorRequest $request)
    {
        $vendor = $this->vendorService->create($request->validated());

        return $this->response(
            'success',
            'Vendor created successfully',
            $vendor->toArray(),
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = trim((string) $request->input('query'));

        if ($query === '') {
            return $this->response('error', 'Please enter a vendor name.', [], 422);
        }

        $vendor = Vendor::query()
            ->where(function ($vendorQuery) use ($query) {
                $search = "%{$query}%";

                $vendorQuery
                    ->where('name', 'LIKE', $search);
            })
            ->get();

        if ($vendor->isEmpty()) {
            return $this->response('error', 'No matching vendors. Try adding one.', [], 404);
        }

        return $this->response(
            'success',
            'Vendors retrieved successfully',
            $vendor->toArray(),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        $vendor = $this->vendorService->update($vendor, $request->validated());

        return $this->response(
            'success',
            'Vendor updated successfully',
            $vendor->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $deleted = $this->vendorService->delete($vendor);
        
        if (! $deleted) {
            return $this->response(
                'error',
                'Unable to delete vendor at this time.',
                [],
                500
            );
        }

        return $this->response(
            'success',
            'Vendor deleted successfully.',
            [],
            200
        );
        
    }  
}
