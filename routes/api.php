<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchSectionController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemTypeCategoryController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OpacSearchController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\SerialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionCredentialController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPermissionController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [LoginController::class, 'index'])->middleware('throttle:login');
    Route::post('/registration', [AuthController::class, 'registration']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');


    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('', [AuthController::class, 'index']); // /api/auth
    });
});

// Public Routes 
Route::get('/try', [TestController::class, 'index']);
Route::get('opac/search', [OpacSearchController::class, 'search'])->middleware('throttle:read');

// Public Routes for Item Types
Route::get('item-types', [ItemTypeController::class, 'index'])->middleware('throttle:read');
Route::get('item-type-categories', [ItemTypeCategoryController::class, 'index'])->middleware('throttle:read');
Route::get('languages', [LanguageController::class, 'index'])->middleware('throttle:read');
Route::get('authors', [AuthorController::class, 'index'])->middleware('throttle:read');

// Media Routes
Route::group(['prefix' => '/media'], function () {
    Route::group(['prefix' => '/create'], function () {
        Route::post('', [MediaController::class, 'upload'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [MediaController::class, 'index'])->middleware('jwt.auth', 'role:super_admin', 'throttle:read');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{media}', [MediaController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{media}', [MediaController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
    });
});

// User Permission Routes
Route::group(['prefix' => '/user-permission'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [UserPermissionController::class, 'index'])->middleware('jwt.auth', 'throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [UserPermissionController::class, 'store'])->middleware('jwt.auth', 'throttle:write_permission');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('/{user_permission}', [UserPermissionController::class, 'update'])->middleware('jwt.auth', 'throttle:write_permission');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('/{user_permission}', [UserPermissionController::class, 'destroy'])->middleware('jwt.auth', 'throttle:delete');
    });

});

// ============== ITEM ROUTES ===============
Route::group(['prefix' => '/item'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [ItemController::class, 'index'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:read');

        /* SUBSCRIPTION ROUTES */
        Route::get('subscriptions', [SubscriptionController::class, 'getResources'])->middleware('throttle:read');
        Route::get('subscription-credential/{subscriptionId}', [SubscriptionCredentialController::class, 'getCredential'])->middleware('throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        /* SUBSCRIPTION ROUTES */
        Route::post('subscription', [SubscriptionController::class, 'store'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');
        Route::post('subscription_credential', [SubscriptionCredentialController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');

        /* ACADEMICS ROUTES */
        Route::post('academic', [AcademicController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');
        /* SERIAL ROUTES */
        Route::post('serial', [SerialController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');
        /* BOOK ROUTES */
        Route::post('book', [BookController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        /* SUBSCRIPTION ROUTES */
        Route::put('subscription/{subscription}', [SubscriptionController::class, 'update'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');
        Route::put('subscription-credential/{subscriptionCredential}', [SubscriptionCredentialController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin,librarian');

        /* ACADEMICS ROUTES */
        Route::put('academic/{academic}', [AcademicController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');
        /* SERIAL ROUTES */
        Route::put('serial/{serial}', [SerialController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');
        /* BOOK ROUTES */
        Route::put('book/{book}', [BookController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        /* SUBSCRIPTION ROUTES */
        Route::delete('subscription/{subscriptionId}', [SubscriptionController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');

        /* ACADEMICS ROUTES */
        Route::delete('academic/{academic}', [AcademicController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:delete');
        /* SERIAL ROUTES */
        Route::delete('serial/{serial}', [SerialController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:delete');
        /* BOOK ROUTES */
        Route::delete('book/{book}', [BookController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin,librarian', 'throttle:delete');
    });
});

// Campus Routes
Route::group(['prefix' => '/campus'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [CampusController::class, 'index']);
        Route::get('{campus}', [CampusController::class, 'show'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:api');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [CampusController::class, 'store'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{campus}', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{campus}', [CampusController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin', 'throttle:delete');
    });

});

// Department Routes
Route::group(['prefix' => '/department'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [DepartmentController::class, 'index'])->middleware('throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [DepartmentController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{department}', [DepartmentController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{department}', [DepartmentController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
    });

});

// Program Routes
Route::group(['prefix' => '/program'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [ProgramsController::class, 'index'])->middleware('throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [ProgramsController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{program}', [ProgramsController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{program}', [ProgramsController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
    });

});

// Branch Routes
Route::group(['prefix' => '/branch'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [BranchController::class, 'index'])->middleware('throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [BranchController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{branch}', [BranchController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{branch}', [BranchController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin', 'throttle:delete');
    });

});

// Section Routes
Route::group(['prefix' => '/section'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [SectionsController::class, 'index'])->middleware('throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [SectionsController::class, 'store'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{section}', [SectionsController::class, 'update'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{section}', [SectionsController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin', 'throttle:delete');
    });

});

// Branch Section Routes
Route::group(['prefix' => '/branch_section'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [BranchSectionController::class, 'index'])->middleware('throttle:read');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [BranchSectionController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{branchSection}', [BranchSectionController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{branchSection}', [BranchSectionController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
    });

});

// User Routes
Route::group(['prefix' => '/user'], function () {

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [UserController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{user}', [UserController::class, 'update'])->middleware('jwt.auth', 'role:super_admin');
    });

});

// Admin Routes
// Route::group(['prefix' => '/admin'], function () {

//     Route::group(['prefix' => '/create'], function () {
//         Route::post('', [AdminController::class, 'store'])->middleware('jwt.auth', 'role:super_admin');
//     });

//     Route::group(['prefix' => '/update'], function () {
//         Route::put('{admin}', [AdminController::class, 'update'])->middleware('jwt.auth', 'role:super_admin');
//     });

//     Route::group(['prefix' => '/delete'], function () {
//         Route::delete('{admin}', [AdminController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin');
//     });
// });
