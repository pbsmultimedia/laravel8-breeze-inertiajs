<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ClientController;

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

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('/');
Route::resource('client', ClientController::class)->middleware(['auth', 'verified'])->only(['index', 'store']);

// should be in the API..
Route::get('/maintenance', [MaintenanceController::class, 'index'])->middleware(['auth', 'verified'])->name('/maintenance');

// tests
Route::get('/cars', [CarController::class, 'index']);
Route::get('/test', [TestController::class, 'index']);

/*
Route::get('/check', function () {
    return Inertia::render('home');
})->middleware(['auth', 'verified'])->name('home');
*/

require __DIR__.'/auth.php';
