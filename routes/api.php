<?php

use App\Http\Controllers\Api\ShiftsController;
use App\Http\Controllers\Api\ShiftSubmissionsController;
use App\Http\Controllers\Api\StaffsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware'=>'api'],function() {
    // staffs
    Route::post('/staffs/updatePassword', [StaffsController::class, 'updatePassword']);
    Route::post('/staffs/get', [StaffsController::class, 'get']);
    Route::post('/staffs/getPayroll', [StaffsController::class, 'getPayroll']);
    Route::post('/staffs/getAttendances', [StaffsController::class, 'getAttendances']);
    Route::post('/staffs/getProvisionAndDeduction', [StaffsController::class, 'getProvisionAndDeduction']);

    // shiftSubmissions
    Route::post('/shiftSubmissions/get', [shiftSubmissionsController::class, 'get']);

    // shifts
    Route::post('/shifts/get', [shiftsController::class, 'get']);
});
