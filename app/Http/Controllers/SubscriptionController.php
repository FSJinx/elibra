<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\SubscriptionCredential;
use App\Models\System;
use App\Models\Branch;

class SubscriptionController extends Controller
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
    public function store(StoreSubscriptionRequest $request)
    {
        // $user = auth('api')->user();
        $user = $this->user();

        $branchId = $user->librarian? $user->librarian->branch_id : null;

        if (!$branchId) {
            return $this->response('error', 'You must be assigned to a branch to perform this action.', null, 400);
        }

        $subscription = Subscription::create([
            'name' => $request->name,
            'description' => $request->description,
            'link' => $request->link,
            'thumbnail_id' => $request->thumbnail_id,
        ]);

      return $this->response(
        'success', 
        'Subscription created successfully', 
        $subscription->toArray(), 
        201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    public function getResources(SubscriptionCredential $subscriptionCredential)
    {
        $user = $this->user();

       $accessibility = System::where('key', 'subscription_visibility')->first();

       $query = Subscription::with('media');

        // Show credentials unless guest + private
        if ($user || $accessibility === 'public') {
            $query->with('subscriptionCredentials');
        }

        $subscriptions = $query->get();

        if ($subscriptions->isEmpty()) {
            return $this->response('error', 'No subscriptions found.', null, 404);
        }

        return $this->response('success', 'Subscriptions retrieved successfully', $subscriptions->toArray(), 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
