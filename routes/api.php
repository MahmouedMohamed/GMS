<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ClientSubscriptionController;
use App\Http\Controllers\API\GymSubscriptionInfoController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::group(['middleware' => 'api_auth'], function () {
    Route::get('/admin', [AdminController::class, 'generalAdminDashboard']);
    Route::apiResource('/gym/subscriptions',GymSubscriptionInfoController::class);
    Route::apiResource('/gym/subscribe',ClientSubscriptionController::class);
});

Route::post('/token/refresh',[TokensController::class,'refresh']);
