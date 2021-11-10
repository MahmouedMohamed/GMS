<?php

use App\Http\Controllers\ClientGymSubscriptionController;
use App\Http\Controllers\ClientTrainerSubscriptionController;
use App\Http\Controllers\GymSubscriptionPlanController;
use App\Http\Controllers\TrainerShiftController;
use App\Http\Controllers\TrainerSubscriptionPlanController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', [AdminController::class, 'generalAdminDashboard']);
Route::apiResource('/gym/subscriptions',GymSubscriptionPlanController::class);
Route::apiResource('/gym/subscribe',ClientGymSubscriptionController::class);
Route::apiResource('/trainer/subscriptions',TrainerSubscriptionPlanController::class);
Route::apiResource('/trainer/subscribe',ClientTrainerSubscriptionController::class);
Route::apiResource('/trainer/shifts',TrainerShiftController::class);
Route::get('/trainer/{id}/shifts',[TrainerShiftController::class,'getSpecificTrainerShifts']);

require __DIR__.'/auth.php';
