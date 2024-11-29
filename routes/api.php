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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// send request from login form 
Route::controller(UserController::class)->group(function () {
    Route::post('login', 'userLogin');
    Route::post('register', 'register');
});

Route::post('jobs/store', [JobController::class, 'store']);
Route::get('jobs/show', [JobController::class, 'index']);
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
Route::resource('permissions', PermissionController::class);
Route::resource('roles', RolesController::class);


// Routes for authenticated user details and logout, using auth:api middleware
Route::middleware('auth:api')->controller(UserController::class)->group(function () {
    Route::get('userDetail', 'getUserDetail');
    Route::get('logout', 'userLogout');
});
