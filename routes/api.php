<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AcquisitionController;
use App\Http\Controllers\AcquisitionLinesController;
use App\Http\Controllers\AcquisitionRequestController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthorshipController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchSectionController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidaysController;
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

/**
 *      Authentication Routes
 *
 *      These routes are grouped into the prefix "auth"
 *      and is used by authenticated users only
 */
Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [LoginController::class, 'index'])->middleware('throttle:login');
    Route::post('/registration', [AuthController::class, 'registration']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('', [AuthController::class, 'index']); // /api/auth
    });
});

/**
 *      Public Routes
 *
 *      This routes are either generic or static in nature.
 *      They provide all the data in the database are usually
 *      used as static data for selects.
 */

// ============== AUTHORS ROUTE ==================
Route::group(['prefix' => '/authors'], function () {
    // Get
    Route::get('', [AuthorController::class, 'index'])->middleware('throttle:read');

    // Post
    Route::post('', [AuthorController::class, 'store'])->middleware('throttle:write');
    Route::post('show', [AuthorController::class, 'show'])->middleware('throttle:write');

    // Update

    // Delete
});

// ============== AUTHORSHIP ROUTE ==================
Route::group(['prefix' => '/authorship'], function () {
    // Get
    Route::get('', [AuthorshipController::class, 'index'])->middleware('throttle:read');

    // Post

    // Update

    // Delete
});

// ============== BRANCH ROUTE ==================
Route::group(['prefix' => '/branch'], function () {
    // Get
    Route::get('', [BranchController::class, 'index'])->middleware('throttle:read');
    Route::get('show', [BranchController::class, 'index'])->middleware('throttle:read');

    // Post
    Route::post('', [BranchController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Update
    Route::put('{branch}', [BranchController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Delete
    Route::delete('{branch}', [BranchController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin', 'throttle:delete');
});

// ============== BRANCH SECTION ROUTE ==================
Route::group(['prefix' => '/branch_section'], function () {
    // Get
    Route::get('', [BranchSectionController::class, 'index'])->middleware('throttle:read');

    // Post
    Route::post('', [BranchSectionController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Update
    Route::put('{branchSection}', [BranchSectionController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Delete
    Route::delete('{branchSection}', [BranchSectionController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
});

// ============== CAMPUS ROUTE ==================
Route::group(['prefix' => '/campus'], function () {
    // Get
    Route::get('', [CampusController::class, 'index']);
    Route::get('{campus}', [CampusController::class, 'show'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:api');

    // Post
    Route::post('', [CampusController::class, 'store'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');

    // Update
    Route::put('{campus}', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');

    // Delete
    Route::delete('{campus}', [CampusController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin', 'throttle:delete');
});

// ============== DEPARTMENT ROUTE ==================
Route::group(['prefix' => '/department'], function () {
    // Get
    Route::get('', [DepartmentController::class, 'index'])->middleware('throttle:read');

    // Post
    Route::post('', [DepartmentController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Update
    Route::put('{department}', [DepartmentController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Delete
    Route::delete('{department}', [DepartmentController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
});

// ============== HOLIDAYS ROUTE ==================
Route::group(['prefix' => '/holidays'], function () {
    // Get
    Route::get('', [HolidaysController::class, 'index']);

    // Post

    // Update

    // Delete
});

// ============== ITEM TYPE ROUTE ==================
Route::group(['prefix' => '/item_types'], function () {
    // Get
    Route::get('', [ItemTypeController::class, 'index'])->middleware('throttle:read');

    // Post

    // Update

    // Delete
});

// ============== ITEM TYPE CATEGORIES ROUTE ==================
Route::group(['prefix' => '/item_type_category'], function () {
    // Get
    Route::get('', [ItemTypeCategoryController::class, 'index'])->middleware('throttle:read');

    // Post

    // Update

    // Delete
});

// ============== LANGUAGES ROUTE ==================
Route::group(['prefix' => '/languages'], function () {
    // Get
    Route::get('', [LanguageController::class, 'index'])->middleware('throttle:read');

    // Post

    // Update

    // Delete
});

// ============== PROGRAM ROUTE ==================
Route::group(['prefix' => '/program'], function () {
    // Get
    Route::get('', [ProgramsController::class, 'index'])->middleware('throttle:read');

    // Post
    Route::post('', [ProgramsController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Update
    Route::put('{program}', [ProgramsController::class, 'update'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:write');

    // Delete
    Route::delete('{program}', [ProgramsController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin,admin', 'throttle:delete');
});

// ============== SECTION ROUTE ==================
Route::group(['prefix' => '/section'], function () {
    // Get
    Route::get('', [SectionsController::class, 'index'])->middleware('throttle:read');

    // Post
    Route::post('', [SectionsController::class, 'store'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');

    // Update
    Route::put('{section}', [SectionsController::class, 'update'])->middleware('jwt.auth', 'role:super_admin', 'throttle:write');

    // Delete
    Route::delete('{section}', [SectionsController::class, 'destroy'])->middleware('jwt.auth', 'role:super_admin', 'throttle:delete');
});

/**
 *      Private Routes
 *
 *      This routes are role-based and module-based.
 *      Librarian and Admin route shares the same parent route
 *      because they both under the same campus. The only difference
 *      they have is the scope of their management.
 *
 *      Librarian - manages his own branch
 *
 *      Admin - manages all branches
 *
 *      all while as long as they are on the same campus.
 */
Route::group(['prefix' => '/librarian'], function () {
    // ============== DASHBOARD ROUTE ==================
    Route::group(['prefix' => 'dashboard'], function () {
        // Get

        // Post

        // Update

        // Delete
    });

    // ============== COLLECTIONS ROUTE ==================
    Route::group(['prefix' => '/collections'], function () {
        // Get
        Route::get('', [AcquisitionController::class, 'index']);

        // Post

        // Update

        // Delete
    });

    // ============== ACQUISITION ROUTE ==================
    Route::group(['prefix' => '/acquisition'], function () {
        // Get
        Route::get('', [AcquisitionController::class, 'index']);

        // Post
        Route::post('', [AcquisitionController::class, 'store'])->middleware('throttle:write');
        Route::post('line', [AcquisitionLinesController::class, 'store'])->middleware('throttle:write');
        Route::post('request', [AcquisitionRequestController::class, 'store'])->middleware('throttle:write');

        // Update
        Route::put('/{acquisition}', [AcquisitionController::class, 'update'])->middleware('throttle:write');
        Route::put('/line/{acquisitionLie}', [AcquisitionLinesController::class, 'update'])->middleware('throttle:write');
        Route::put('/request/{acquisitionRequest}', [AcquisitionRequestController::class, 'update'])->middleware('throttle:write');

        // Delete
        Route::delete('/{acquisition}', [AcquisitionController::class, 'destroy'])->middleware('throttle:delete');
    });

})->middleware('jwt.auth', 'role:admin,librarian');

/**
 *      Dead Zone
 *
 *      All the routes under this are either temporary or permanent.
 *      Routes under this description might or might not be currently being
 *      utilized by the frontend. This zone is the dead zone. All the routes under here
 *      will undergo checking. The checking will be conducted by the lead developers
 *      before undergoing changes. Do not touch anything under here.
 */

// Public Routes
Route::get('/try', [TestController::class, 'index']);
Route::get('opac/search', [OpacSearchController::class, 'search'])->middleware('throttle:read');

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

// User Routes
Route::group(['prefix' => '/user'], function () {

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [UserController::class, 'store'])->middleware('jwt.auth', 'role:super_admin,admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{user}', [UserController::class, 'update'])->middleware('jwt.auth', 'role:super_admin');
    });

});
