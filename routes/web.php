<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
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

Route::get('/', [ListingController::class, 'index']);

Route::middleware('auth')->group(function () {

    Route::post('/listings', [ListingController::class, 'store']);

    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit']);

    Route::put('/listings/{listing}', [ListingController::class, 'update']);

    Route::get('/listings/create', [ListingController::class, 'create']);

    Route::get('/listings/{listing:id}', [ListingController::class, 'show']);

    Route::delete('/listings/{listing:id}', [ListingController::class, 'destroy']);
});

// Users

Route::get('/register', [UserController::class, 'create'])->middleware('guest');
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/authenticate', [UserController::class, 'authenticate']);
