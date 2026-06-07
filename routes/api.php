<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\PatronController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






// Authentication Routes
Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registration', [AuthController::class, 'registration']);
});




// Admin Routes
Route::group(['prefix' => '/admin', 'middleware' => ['auth:api', 'role:admin']], function () {
    
    Route::group(['prefix' => 'get'], function () {
        Route::get('user', [AdminController::class, 'getUser']);
    });

    Route::group(['prefix' => 'create'], function () {
        Route::post('user', [AdminController::class, 'storeUser']);
    });
});

// Librarian Routes
Route::group(['prefix' => '/librarian', 'middleware' => ['auth:api', 'role:librarian']], function () {
    
    Route::group(['prefix' => 'get'], function () {
        Route::get('user', [AdminController::class, 'getUser']);
    });

    Route::group(['prefix' => 'create'], function () {
        Route::post('user', [AdminController::class, 'storeUser']);
    });
});

// Patron Routes
Route::group(['prefix' => '/patron', 'middleware' => ['auth:api', 'role:patron']], function () {
    
    Route::group(['prefix' => 'get'], function () {
        Route::get('user', [AdminController::class, 'getUser']);
    });

    Route::group(['prefix' => 'create'], function () {
        Route::post('user', [AdminController::class, 'storeUser']);
    });
});



Route::get('/campus', [CampusController::class, 'index']);

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

