<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionCredential;
use App\Http\Requests\StoreSubscriptionCredentialRequest;
use App\Http\Requests\UpdateSubscriptionCredentialRequest;
use App\Models\System;
use App\Models\Branch;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubscriptionCredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreSubscriptionCredentialRequest $request)
    {
        DB::beginTransaction();
        try {
            $subscriptionCredential = SubscriptionCredential::create($request->validated());
            
            DB::commit();
            CacheService::invalidate(CacheService::SUBSCRIPTION_CREDENTIALS);

            return $this->response(
                'success',
                'Subscription credential created successfully',
                $subscriptionCredential->toArray(),
                201
            );

        } catch(Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function getCredential($subscriptionId)
    {
        $user = $this->user();

        $visibility = System::where('key', 'subscription_visibility')->value('value');

        // Guests cannot access credentials when visibility is private
        if (! $user && $visibility === 'private') {
            return $this->response(
                'error',
                'You must be logged in to access this resource.',
                null,
                401
            );
        }

        $campusId = $user?->isSuperAdmin() ? 'all' : ($user?->campus_id ?? 'guest');

        $cacheKey = CacheService::SUBSCRIPTION_CREDENTIALS
            . ":subscription:{$subscriptionId}:campus:{$campusId}";

        $credential = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($subscriptionId, $user) {
            $query = SubscriptionCredential::where('subscription_id', $subscriptionId);

            // Admins and Librarians can only access credentials from their own campus
            if ($user && ! $user->isSuperAdmin()) {
                $query->where('campus_id', $user->campus_id);
            }

            return $query->first();
        });

        if (! $credential) {
            return $this->response(
                'error',
                'No credentials found for the specified subscription.',
                null,
                404
            );
        }

        return $this->response(
            'success',
            'Subscription credential retrieved successfully',
            $credential->toArray(),
            200
        );
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionCredential $subscriptionCredential)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionCredentialRequest $request, SubscriptionCredential $subscriptionCredentialId)
    {
        DB::beginTransaction();
        try {
            $subscriptionCredentialId->update($request->validated());
            
            DB::commit();
            CacheService::invalidate(CacheService::SUBSCRIPTION_CREDENTIALS);

            return $this->response(
                'success',
                'Subscription credential updated successfully',
                $subscriptionCredentialId->toArray(),
                200
            );
        } catch(Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionCredential $subscriptionCredential)
    {
        $this->authorize('delete', $subscriptionCredential);

        DB::beginTransaction();
        try {
            $subscriptionCredential->delete();
            DB::commit();

            CacheService::invalidate(CacheService::SUBSCRIPTION_CREDENTIALS);

            return $this->response(
                'success',
                'Subscription credential deleted successfully',
                null,
                200
            );
        } catch(Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
