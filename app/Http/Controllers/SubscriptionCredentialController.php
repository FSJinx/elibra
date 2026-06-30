<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionCredential;
use App\Http\Requests\StoreSubscriptionCredentialRequest;
use App\Http\Requests\UpdateSubscriptionCredentialRequest;
use App\Models\System;
use App\Models\Branch;

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
        $user = auth('api')->user();

        $branchId = $user->librarian? $user->librarian->branch_id : null;

        if (!$branchId) {
            return $this->response('error', 'You must be assigned to a branch to perform this action.', null, 400);
        }

        $credential = SubscriptionCredential::create([
            'username' => $request->username,
            'password' => $request->password,

            'subscription_id' => $request->subscription_id,
            'campus_id' => $request->campus_id,
        ]);

        return $this->response(
            'success', 
            'Subscription credential added successfully', 
            $credential->toArray(),
            // null,
            201);
    }

    /**
     * Display the specified resource.
     */
    public function getCredential($subscriptionId)
    {
       $user = $this->user();

       $accessibility = System::where('key', 'subscription_visibility')->first();

       //Check Accessibility Value if private
       if(!$user &&$accessibility->value === 'private'){
            return $this->response('error', 'You must be logged in to access this resource.', null, 401);
       }
    
        // If accessibility is public, retrieve the subscription credentials based on the subscription ID
        $credential = SubscriptionCredential::where('subscription_id', $subscriptionId)->first();

        if(!$credential) {
            return $this->response('error', 'No credentials found for the specified subscription.', null, 404);
        }
        
        return $this->response(
            'success', 
            'Subscription credential decoded successfully', 
            $credential ? $credential->toArray() : null,
        200);
    
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
    public function update(UpdateSubscriptionCredentialRequest $request, SubscriptionCredential $subscriptionCredential)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionCredential $subscriptionCredential)
    {
        //
    }
}
