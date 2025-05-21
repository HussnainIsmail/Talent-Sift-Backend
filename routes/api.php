<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\API\EnhanceUserProfileController;
use App\Http\Controllers\API\JobApplicationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\ResemeController;
use App\Http\Controllers\API\UserProfileController;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// send request from login form 
Route::controller(UserController::class)->group(function () {
    Route::post('login', 'userLogin');
    Route::post('register', 'register');
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});

// for job list in admin panel job
Route::get('/job/edit/{id}', [JobController::class, 'edit']);
Route::get('jobs/show', [JobController::class, 'show']);


Route::middleware(['auth:api', 'role:recuriter'])->group(function () {
    Route::get('companies', [CompanyController::class, 'index']);
    Route::get('companies/create', [CompanyController::class, 'create']);
    Route::post('companies/store', [CompanyController::class, 'store']);
    Route::post('/jobs/store', [JobController::class, 'store']);
    Route::get('jobs', [JobController::class, 'index']);
    Route::get('jobs/{id}/edit', [JobController::class, 'edit']);
    Route::put('/job/update/{id}', [JobController::class, 'update']);
    Route::delete('jobs/{id}', [JobController::class, 'destroy']);
    Route::get('/jobs/{jobId}/applications', [ResemeController::class, 'index']);
    Route::get('download-cv/{filename}', function ($filename) {
        $filePath = public_path("storage/cvs/{$filename}");
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        return response()->json(['message' => 'File not found'], 404);
    });
    Route::get('/application/{applicationId}', [ResemeController::class, 'show']);
    Route::post('/send/interview-email/{applicationId}', [ResemeController::class, 'sendInterviewEmail']);
});
Route::middleware('auth:api')->get('user/profile', [UserProfileController::class, 'index']);
Route::middleware('auth:api')->post('user/update-profile/{id}', [UserProfileController::class, 'updateProfile']);
Route::middleware('auth:api')->post('user/enhance-profile', [EnhanceUserProfileController::class, 'enhanceProfile']);
Route::middleware('auth:api')->get('/user/enhance-profile', [EnhanceUserProfileController::class, 'getEnhancedProfile']);







// Route::middleware(['auth:api', 'role:super-admin'])->group(function () {

// });
// Route::middleware(['auth:api', 'role:admin', 'role:recruiter'])->group(function () {

// });

Route::post('/applications/store', [JobApplicationController::class, 'store']);



Route::middleware('auth:api', 'role:admin')->group(function () {
    Route::get('company/list', [UserController::class, 'companylist']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'edit']);
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RolesController::class);
});

Route::middleware('auth:api', 'role:user')->group(function () {

    Route::get('logout', [UserController::class, 'userLogout']);

    // Route::get('jobs/{job}/applications', [JobController::class, 'index']);
});


Route::middleware('auth:api')->get('/check-auth', function (Request $request) {
    return response()->json(['authenticated' => true]);
});
