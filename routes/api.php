<?php

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

Route::group(['middleware'=>'api'],function(){
    Route::post('/staffs/updatePassword', [StaffsController::class, 'updatePassword']);
    Route::post('/staffs/getStaff', [StaffsController::class, 'getStaff']);
    Route::post('/staffs/getPayroll', [StaffsController::class, 'getPayroll']);
    Route::post('/staffs/getAttendances', [StaffsController::class, 'getAttendances']);
});
