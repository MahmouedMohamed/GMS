<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ClientGymSubscriptionController;
use App\Http\Controllers\API\ClientTrainerSubscriptionController;
use App\Http\Controllers\API\GymSubscriptionPlanController;
use App\Http\Controllers\API\TrainerSubscriptionPlanController;
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

// Route::group(['middleware' => 'api_auth'], function () {
    Route::get('/admin', [AdminController::class, 'generalAdminDashboard']);
    Route::apiResource('/gym/subscriptions',GymSubscriptionPlanController::class);
    Route::apiResource('/gym/subscribe',ClientGymSubscriptionController::class);
    Route::apiResource('/trainer/subscriptions',TrainerSubscriptionPlanController::class);
    Route::apiResource('/trainer/subscribe',ClientTrainerSubscriptionController::class);

Route::post('/token/refresh',[TokensController::class,'refresh']);
