<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\JobController;

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

Route::post('jobs/store',[JobController::class,'store']);



// Routes for authenticated user details and logout, using auth:api middleware
Route::middleware('auth:api')->controller(UserController::class)->group(function () {
    Route::get('userDetail', 'getUserDetail');
    Route::get('logout', 'userLogout');
});
