<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpenShiftsController;
use App\Http\Controllers\ShiftRequestController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::post('/login_user', [App\Http\Controllers\OpenShiftsController::class, 'login_user'])->name('login_user');

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('checkRole')->name('home');
Route::get('/home2', [App\Http\Controllers\HomeController::class, 'index']);

Route::group(['prefix' => 'open-shifts', 'middleware' => 'checkRole'], function () {
    Route::get('/', [OpenShiftsController::class, 'index'])->name('open_shifts');
    Route::post('/store', [OpenShiftsController::class, 'store'])->name('shift-request');
    Route::get('shift_edit/{id}', [OpenShiftsController::class, 'edit'])->name('shift.edit');
    Route::patch('shift_update/{id}', [OpenShiftsController::class, 'update'])->name('shift.update');
    Route::delete('destroy/{id}', [OpenShiftsController::class, 'destroy'])->name('shift.destroy');
});

Route::group(['prefix' => 'shift-request'], function () {
    Route::get('/', [ShiftRequestController::class, 'index'])->name('open_shifts');
    Route::post('/', [ShiftRequestController::class, 'store_shift'])->name('open_shifts');

    Route::post('/accept/{id}', [ShiftRequestController::class, 'accept'])->name('shift-requests.accept');
    Route::post('/{id}/reject', [ShiftRequestController::class, 'reject'])->name('shift-requests.reject');
    Route::get('approved-request', [ShiftRequestController::class, 'approved_request']);
    Route::get('rejected-request', [ShiftRequestController::class, 'rejected_request']);
    Route::get('/shift-requests/{id}/reject', [ShiftRequestController::class, 'showRejectionForm'])->name('shift-requests.reject-form');
    Route::post('/shift-requests/{id}/reject', [ShiftRequestController::class, 'reject'])->name('shift-requests.reject');
    Route::get('/get-shifts', [ShiftRequestController::class, 'calendar']);
});
Route::get('/show-calendar', [ShiftRequestController::class, 'showCalendarView'])->middleware('checkRole');
Route::post('/mark-notification-as-read/{id}', [NotificationController::class, 'markAsRead']);
Route::post('/delete-notification/{id}', [NotificationController::class, 'delete']);


Route::get('/add_admin', [DashboardController::class, 'add_admin']);


Route::get('/update_profile', [DashboardController::class, 'update_profile']);
Route::post('/update_profile', [DashboardController::class, 'update_profile_data']);
