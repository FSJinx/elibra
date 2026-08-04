<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\SubscriptionCredential;
use App\Models\Subscription;
use App\Models\System;
use App\Models\Branch;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

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
    public function store(StoreSubscriptionRequest $request)
    {
        DB::beginTransaction();
        try {

            $subscription = Subscription::create($request->validated());
            
            DB::commit();
            CacheService::invalidate(CacheService::SUBSCRIPTIONS);

            return $this->response(
                'success', 
                'Subscription created successfully', 
                $subscription->toArray(), 
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();
           
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    public function getResources()
    {
        $user = $this->user();

            $accessibility = System::where('key', 'subscription_visibility')->value('value');

            $subscriptions = CacheService::remember(
                CacheService::SUBSCRIPTIONS,
                [
                    'user' => (bool) $user,
                    'visibility' => $accessibility,
                ],
                now()->addMinutes(30),
                function () use ($user, $accessibility) {

                    $query = Subscription::with('media');

                    if ($user || $accessibility === 'public') {
                        $query->with('subscriptionCredentials');
                    }

                    return $query->get();
                }
            );

            return $this->response(
                'success',
                'Subscriptions retrieved successfully',
                $subscriptions->toArray(),
                200
            );
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        DB::beginTransaction();
        try {
            $subscription->update($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::SUBSCRIPTIONS);

            return $this->response(
                'success',
                'Subscription updated successfully',
                $subscription->toArray(),
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscriptionId)
    {

        $this->authorize('delete', $subscriptionId);
        DB::beginTransaction();
        try {
            $subscriptionId->delete();

            DB::commit();
            CacheService::invalidate(CacheService::SUBSCRIPTIONS);

            return $this->response(
                'success', 
                'Subscription deleted successfully', 
                null, 
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
