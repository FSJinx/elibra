<?php
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionCredentialController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchSectionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPermissionController;
use App\Models\BranchSection;
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

Route::post('/upload-media', [MediaController::class, 'upload'])->middleware('jwt.auth', 'role:admin');

//User Permission Routes
Route::group(['prefix' => '/user-permission'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [UserPermissionController::class, 'index'])->middleware('jwt.auth');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [UserPermissionController::class, 'store'])->middleware('jwt.auth');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('/{user_permission}', [UserPermissionController::class, 'update'])->middleware(('jwt.auth'));
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('/{user_permission}', [UserPermissionController::class, 'destroy'])->middleware('jwt.auth');
    });

});

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
        Route::get('{campus}', [CampusController::class, 'show'])->middleware('jwt.auth', 'role:super admin');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [CampusController::class, 'store'])->middleware('jwt.auth', 'role:super admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{campus}', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:super admin');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{campus}', [CampusController::class, 'destroy'])->middleware('jwt.auth', 'role:super admin');
    });

});

// Department Routes
Route::group(['prefix' => '/department'], function () {

    Route::group(['prefix' => '/get'], function () {
        // Route::get('', [CampusController::class, 'index']);
        // Route::get('{campus}', [CampusController::class, 'show'])->middleware('jwt.auth', 'role:super admin');
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [DepartmentController::class, 'store'])->middleware('jwt.auth', 'role:super admin');
    });

    Route::group(['prefix' => '/update'], function () {
        // Route::put('{campus}', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:super admin');
    });

    Route::group(['prefix' => '/delete'], function () {
        // Route::delete('{campus}', [CampusController::class, 'destroy'])->middleware('jwt.auth', 'role:super admin');
    });

});

//Branch Routes
Route::group(['prefix' => '/branch'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [BranchController::class, 'index']);
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [BranchController::class, 'store'])->middleware('jwt.auth', 'role:admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{branch}', [BranchController::class, 'update'])->middleware('jwt.auth', 'role:librarian,admin');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{branch}', [BranchController::class, 'destroy'])->middleware('jwt.auth', 'role:admin');
    });

});

// Section Routes
Route::group(['prefix' => '/section'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [SectionsController::class, 'index']);
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [SectionsController::class, 'store'])->middleware('jwt.auth', 'role:admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{section}', [SectionsController::class, 'update'])->middleware('jwt.auth', 'role:admin');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{section}', [SectionsController::class, 'destroy'])->middleware('jwt.auth', 'role:admin');
    });

});

//Branch Section Routes
Route::group(['prefix' => '/branch_section'], function () {

    Route::group(['prefix' => '/get'], function () {
        Route::get('', [BranchSectionController::class, 'index']);
    });

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [BranchSectionController::class, 'store'])->middleware('jwt.auth');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{branchSection}', [BranchSectionController::class, 'update'])->middleware('jwt.auth');
    });

    Route::group(['prefix' => '/delete'], function () {
        Route::delete('{branchSection}', [BranchSectionController::class, 'destroy'])->middleware('jwt.auth');
    });

});

//User Routes
Route::group(['prefix' => '/user'], function () {

    Route::group(['prefix' => '/create'], function () {
        Route::post('', [UserController::class, 'store'])->middleware('jwt.auth', 'role:super admin,admin');
    });

    Route::group(['prefix' => '/update'], function () {
        Route::put('{user}', [UserController::class, 'update'])->middleware('jwt.auth', 'role:super admin');
    });

});

//Admin Routes
// Route::group(['prefix' => '/admin'], function () {

//     Route::group(['prefix' => '/create'], function () {
//         Route::post('', [AdminController::class, 'store'])->middleware('jwt.auth', 'role:super admin');
//     });
    
//     Route::group(['prefix' => '/update'], function () {
//         Route::put('{admin}', [AdminController::class, 'update'])->middleware('jwt.auth', 'role:super admin');
//     });

//     Route::group(['prefix' => '/delete'], function () {
//         Route::delete('{admin}', [AdminController::class, 'destroy'])->middleware('jwt.auth', 'role:super admin');
//     });
// });
