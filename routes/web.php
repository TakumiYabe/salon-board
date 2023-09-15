<?php

use App\Http\Controllers\Api\ShiftsController;
use App\Http\Controllers\Api\ShiftSubmissionsController;
use App\Http\Controllers\Api\ShiftTypesController;
use App\Http\Controllers\Api\StaffsController;
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

// staff
Route::get('/staffs/index', [StaffsController::class, 'index'])->name('staffs.index');
Route::match(['get', 'post'], '/staffs/edit/{id?}', [StaffsController::class, 'edit'])->name('staffs.edit');
Route::get('/staffs/void/{id}', [StaffsController::class, 'void'])->name('staffs.void');
Route::get('/staffs/un-void/{id}', [StaffsController::class, 'unVoid'])->name('staffs.un-void');
Route::get('/staffs/display-payroll/{id}', [StaffsController::class, 'displayPayroll'])->name('staffs.display-payroll');
Route::get('/staffs/display-attendances/{id}', [StaffsController::class, 'displayAttendances'])->name('staffs.display-attendances');
Route::get('/staffs/display-provision-and-deduction/{id}', [StaffsController::class, 'displayProvisionAndDeduction'])->name('staffs.display-provision-and-deduction');
Route::match(['get', 'post'], '/staffs/editProvisionAndDeduction/{staff_id}', [StaffsController::class, 'editProvisionAndDeduction'])->name('staffs.edit-provision-and-deduction');

// shiftTypes
Route::match(['get', 'post'],'/shift-types/edit', [ShiftTypesController::class, 'edit'])->name('shiftTypes.edit');

// shiftSubmissions
Route::match(['get', 'post'],'/shift-submissions/display/{id}', [ShiftSubmissionsController::class, 'display'])->name('shiftSubmissions.display');
Route::match(['get', 'post'], '/shift-submissions/edit/{staff_id}', [ShiftSubmissionsController::class, 'edit'])->name('shiftSubmissions.edit');

Route::match(['get', 'post'],'/shifts/display', [ShiftsController::class, 'display'])->name('shifts.display');
Route::post('/shifts/edit', [ShiftsController::class, 'edit'])->name('shifts.edit');
