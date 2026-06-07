<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\PatronController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/campus', [CampusController::class, 'index']);

Route::middleware('jwt.auth')->group(function () {

    // Authentication
    Route::get('/user', [AuthController::class, 'user']);

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::post('/', [UsersController::class, 'store']);
    });

    // Librarians
    Route::prefix('librarians')->group(function () {
        Route::get('/', [LibrarianController::class, 'index']);
        Route::post('/', [LibrarianController::class, 'store']);
    });

    // Patrons
    Route::prefix('patrons')->group(function () {
        Route::get('/', [PatronController::class, 'index']);
        Route::post('/', [PatronController::class, 'store']);
    });

});

