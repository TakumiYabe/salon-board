<?php

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


Route::get('/staffs/index', 'App\Http\Controllers\Api\StaffsController@index')->name('staffs.index');
Route::match(['get', 'post'], '/staffs/edit', 'App\Http\Controllers\Api\StaffsController@edit')->name('staffs.create');
Route::match(['get', 'post'], '/staffs/edit/{id}', 'App\Http\Controllers\Api\StaffsController@edit')->name('staffs.edit');
