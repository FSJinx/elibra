<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\PatronController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionCredentialController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UsersController;
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

Route::get('/campus', [CampusController::class, 'index']);

Route::post('/upload-media', [MediaController::class, 'upload'])->middleware('auth:api');

//Item Routes
Route::group(['prefix' => '/item'], function () {

    Route::group(['prefix' => '/get'], function() {
        Route::get('subscriptions', [SubscriptionController::class, 'getResources']);
        Route::get('subscription-credential/{subscriptionId}', [SubscriptionCredentialController::class, 'getCredential']);
    });

    Route::group(['prefix' => '/create'], function() {
        /* SUBSCRIPTION ROUTES */
        Route::post('subscription', [SubscriptionController::class, 'store'])->middleware('jwt.auth', 'role:librarian,admin');
        Route::post('subscription_credential', [SubscriptionCredentialController::class, 'store'])->middleware('jwt.auth', 'role:librarian,admin');

        /* ACADEMICS ROUTES */
        Route::post('academic', [AcademicController::class, 'store'])->middleware('jwt.auth', 'role:librarian');
    });

    Route::group(['prefix' =>'/update'], function() {
        Route::put('subscription-credential/{subscriptionCredentialId}', [SubscriptionCredentialController::class, 'update'])->middleware('jwt.auth', 'role:librarian,admin');
        Route::put('academic/{academic}', [AcademicController::class, 'update'])->middleware('jwt.auth', 'role:librarian');

    });

    Route::group(['prefix' =>'/delete'], function() {
        Route::delete('subscription/{subscriptionId}', [SubscriptionController::class, 'destroy'])->middleware('jwt.auth', 'role:librarian,admin');
        Route::delete('academic/{academic}', [AcademicController::class, 'destroy'])->middleware('jwt.auth', 'role:librarian');
    });
});

//Librarian Routes
Route::group(['prefix' => '/librarian'], function () {

    Route::group(['prefix' => '/get'], function(){

    });

    Route::group(['prefix' => '/create'], function(){
        Route::post('account', [LibrarianController::class, 'store'])->middleware('auth:api', 'role:librarian');
    });

    Route::group(['prefix' => '/update'], function(){

    });

    Route::group(['prefix' => '/delete'], function(){

    });
});

// Users Route
Route::group(['prefix' => '/users'], function () {
    Route::get('', [UsersController::class, 'index']);
});

// // Admin Routes
// Route::group(['prefix' => '/admin', 'middleware' => ['auth:api', 'role:admin']], function () {

//     Route::group(['prefix' => 'get'], function () {
//         Route::get('user', [AdminController::class, 'getUser']);
//     });

//     Route::group(['prefix' => 'create'], function () {
//         Route::post('user', [AdminController::class, 'storeUser']);
//     });
// });

// // Librarian Routes
// Route::group(['prefix' => '/librarian', 'middleware' => ['auth:api', 'role:librarian']], function () {

//     Route::group(['prefix' => 'get'], function () {
//         Route::get('user', [AdminController::class, 'getUser']);
//     });

//     Route::group(['prefix' => 'create'], function () {
//         Route::post('user', [AdminController::class, 'storeUser']);

//     });

// });

// // Patron Routes
// Route::group(['prefix' => '/patron', 'middleware' => ['auth:api', 'role:patron']], function () {

//     Route::group(['prefix' => 'get'], function () {
//         Route::get('user', [AdminController::class, 'getUser']);
//     });

//     Route::group(['prefix' => 'create'], function () {
//         Route::post('user', [AdminController::class, 'storeUser']);
//     });
// });

// Route::middleware('jwt.auth')->group(function () {

//     // Authentication
//     Route::get('/user', [AuthController::class, 'user']);

//     // Users
//     Route::prefix('users')->group(function () {
//         Route::get('/', [UsersController::class, 'index']);
//         Route::post('/', [UsersController::class, 'store']);
//     });

//     // Librarians
//     Route::prefix('librarians')->group(function () {
//         Route::get('/', [LibrarianController::class, 'index']);
//         Route::post('/', [LibrarianController::class, 'store']);
//     });

//     // Patrons
//     Route::prefix('patrons')->group(function () {
//         Route::get('/', [PatronController::class, 'index']);
//         Route::post('/', [PatronController::class, 'store']);
//     });

// });
