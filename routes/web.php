<?php

use App\Http\Controllers\ClientGymSubscriptionController;
use App\Http\Controllers\ClientTrainerSubscriptionController;
use App\Http\Controllers\GymSubscriptionPlanController;
use App\Http\Controllers\HomeController;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/', [HomeController::class, 'index']);
// Route::get('/admin', [AdminController::class, 'generalAdminDashboard']);

//To Make URL Changes Dynamically without affecting Views
Route::resource('/gym/subscriptions', GymSubscriptionPlanController::class)->names([
    'index' => 'gym.subscriptions.index',
    'show' => 'gym.subscriptions.show',
    'create' => 'gym.subscriptions.create',
    'store' => 'gym.subscriptions.store',
    'edit' => 'gym.subscriptions.edit',
    'update' => 'gym.subscriptions.update',
    'destroy' => 'gym.subscriptions.destroy',
]);
Route::resource('/gym/subscribe', ClientGymSubscriptionController::class)->names([
    'index' => 'gym.subscribe.index',
    'show' => 'gym.subscribe.show',
    'create' => 'gym.subscribe.create',
    'store' => 'gym.subscribe.store',
    'edit' => 'gym.subscribe.edit',
    'update' => 'gym.subscribe.update',
    'destroy' => 'gym.subscribe.destroy',
]);
Route::resource('/trainer/subscriptions', TrainerSubscriptionPlanController::class)->names([
    'index' => 'trainer.subscriptions.index',
    'show' => 'trainer.subscriptions.show',
    'create' => 'trainer.subscriptions.create',
    'store' => 'trainer.subscriptions.store',
    'edit' => 'trainer.subscriptions.edit',
    'update' => 'trainer.subscriptions.update',
    'destroy' => 'trainer.subscriptions.destroy',
]);
Route::resource('/trainer/subscribe', ClientTrainerSubscriptionController::class)->names([
    'index' => 'trainer.subscribe.index',
    'show' => 'trainer.subscribe.show',
    'create' => 'trainer.subscribe.create',
    'store' => 'trainer.subscribe.store',
    'edit' => 'trainer.subscribe.edit',
    'update' => 'trainer.subscribe.update',
    'destroy' => 'trainer.subscribe.destroy',
]);
Route::resource('/trainer/shifts', TrainerShiftController::class)->names([
    'index' => 'trainer.shifts.index',
    'show' => 'trainer.shifts.show',
    'create' => 'trainer.shifts.create',
    'store' => 'trainer.shifts.store',
    'edit' => 'trainer.shifts.edit',
    'update' => 'trainer.shifts.update',
    'destroy' => 'trainer.shifts.destroy',
]);
Route::get('/trainer/{id}/shifts', [TrainerShiftController::class, 'getSpecificTrainerShifts'])
    ->name('trainer.shifts.self');

require __DIR__ . '/auth.php';
