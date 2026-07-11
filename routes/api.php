<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionCredentialController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [LoginController::class, 'index']);
    Route::post('/registration', [AuthController::class, 'registration']);

    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('', [AuthController::class, 'index']); // /api/auth
    });
});

// Public Routes
Route::get('/try', [TestController::class, 'index']);

Route::post('/upload-media', [MediaController::class, 'upload'])->middleware('auth:api');

// Item Routes
Route::group(['prefix' => '/item'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('subscriptions', [SubscriptionController::class, 'getResources']);
        Route::get('subscription-credential/{subscriptionId}', [SubscriptionCredentialController::class, 'getCredential']);
    });

    Route::group(['prefix' => '/create'], function () {
        /* SUBSCRIPTION ROUTES */
        Route::post('subscription', [SubscriptionController::class, 'store'])->middleware('jwt.auth', 'role:librarian,admin');
        Route::post('subscription_credential', [SubscriptionCredentialController::class, 'store'])->middleware('jwt.auth', 'role:librarian,admin');

        /* ACADEMICS ROUTES */
        Route::post('academic', [AcademicController::class, 'store'])->middleware('jwt.auth', 'role:librarian');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('subscription-credential/{subscriptionCredentialId}', [SubscriptionCredentialController::class, 'update'])->middleware('jwt.auth', 'role:librarian,admin');
        Route::put('academic/{academic}', [AcademicController::class, 'update'])->middleware('jwt.auth', 'role:librarian');

    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('subscription/{subscriptionId}', [SubscriptionController::class, 'destroy'])->middleware('jwt.auth', 'role:librarian,admin');
        Route::delete('academic/{academic}', [AcademicController::class, 'destroy'])->middleware('jwt.auth', 'role:librarian');
    });
});

// Campus Routes
Route::group(['prefix' => '/campus'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [CampusController::class, 'index']);
        Route::get('{campus}', [CampusController::class, 'show'])->middleware('jwt.auth', 'role:admin');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [CampusController::class, 'store'])->middleware('jwt.auth', 'role:admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{campus}', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:admin');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{campus}', [CampusController::class, 'destroy'])->middleware('jwt.auth', 'role:admin');
    });

});
