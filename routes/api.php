<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenueController;
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

//User Authentication
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

//Venue Routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
    Route::PUT('/venues/{id}', [VenueController::class, 'update'])->name('venues.update');
    Route::DELETE('/venues/{id}', [VenueController::class, 'destroy'])->name('venues.destroy');
});

