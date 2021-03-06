<?php

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/token/show', [App\Http\Controllers\HomeController::class, 'load']);
Route::post('/token/update', [App\Http\Controllers\HomeController::class, 'updateUser']);
Route::middleware('auth')->post('/start', [App\Http\Controllers\HomeController::class, 'start'])->name('start');
Route::middleware('auth')->post('/stop', [App\Http\Controllers\HomeController::class, 'stop'])->name('stop');
Route::middleware('auth')->post('/status', [App\Http\Controllers\HomeController::class, 'status'])->name('status');
