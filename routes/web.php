<?php

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


Route::get('/staffs/index', [StaffsController::class, 'index'])->name('staffs.index');
Route::match(['get', 'post'], '/staffs/edit/{id?}', [StaffsController::class, 'edit'])->name('staffs.edit');
Route::get('/staffs/void/{id}', [StaffsController::class, 'void'])->name('staffs.void');
Route::get('/staffs/un-void/{id}', [StaffsController::class, 'unVoid'])->name('staffs.un-void');
Route::get('/staffs/display-payroll/{id}', [StaffsController::class, 'displayPayroll'])->name('staffs.display-payroll');
Route::get('/staffs/display-attendances/{id}', [StaffsController::class, 'displayAttendances'])->name('staffs.display-attendances');
