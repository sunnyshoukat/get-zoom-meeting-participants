<?php

use App\Http\Controllers\SheetController;
use App\Http\Controllers\ZoomController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [ZoomController::class, 'index']);
Route::get('/get', [SheetController::class, 'get'])->name('get');
Route::get('/empty', [SheetController::class, 'empty'])->name('set.empty');
Route::post('/get/participants', [ZoomController::class, 'getParticipantes'])->name('get.participants');
