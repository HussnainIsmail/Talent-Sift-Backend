<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\API\JobApplicationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolesController;

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
});
Route::get('jobs/show', [JobController::class, 'index']);
Route::resource('permissions', PermissionController::class);
Route::resource('roles', RolesController::class);
Route::get('/job/edit/{id}', [JobController::class, 'edit']);

Route::middleware('auth:api')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'edit']);
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::get('logout', [UserController::class, 'userLogout']);
    Route::post('companies/store', [CompanyController::class, 'store']);
    Route::post('jobs/store', [JobController::class, 'store']);
    Route::get('jobs', [JobController::class, 'index']);
    Route::get('jobs/{id}/edit', [JobController::class, 'edit']);
    Route::put('/job/update/{id}', [JobController::class, 'update']);
    Route::delete('jobs/{id}', [JobController::class, 'destroy']);

    Route::post('companies/store', [CompanyController::class, 'store']);
    Route::post('applications/store', [JobApplicationController::class, 'store']);
    Route::get('applications/list', [JobApplicationController::class, 'index']);
    Route::get('download-cv/{filename}', function ($filename) {
        $filePath = public_path("storage/cvs/{$filename}");
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        return response()->json(['message' => 'File not found'], 404);
    });
});





Route::middleware('auth:api')->get('/check-auth', function (Request $request) {
    return response()->json(['authenticated' => true]);
});
