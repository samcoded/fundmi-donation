<?php

use App\Http\Controllers\DonateController;
use App\Http\Controllers\DonorController;
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

//Donations
Route::get('/', [DonateController::class, 'index']);

Route::get('/donations/create', [DonateController::class, 'create']);

Route::post('/donations', [DonateController::class, 'store']);

Route::get('/donations/manage', [DonateController::class, 'manage']);

Route::get('/donations/{donationlisting}', [DonateController::class, 'show']);

Route::get('/donations/{donationlisting}/edit', [DonateController::class, 'edit'])->middleware('auth');

Route::put('/donations/{donationlisting}', [DonateController::class, 'update'])->middleware('auth');

Route::delete('/donations/{donationlisting}', [DonateController::class, 'destroy'])->middleware('auth');

//Donate
Route::get('/donate/verify/{donationlisting}', [DonorController::class, 'verify_payment']);

Route::get('/donate/{donationlisting}', [DonorController::class, 'create']);

Route::post('/donate/{donationlisting}', [DonorController::class, 'initialize_payment']);

// Show Register/Create Form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Create New User
Route::post('/users', [UserController::class, 'store']);

// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show Login Form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Log In User
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

Route::get('/withdraw', [UserController::class, 'create_withdraw'])->middleware('auth');

Route::post('/withdraw', [UserController::class, 'withdraw'])->middleware('auth');

Route::get('/profile', [UserController::class, 'show'])->middleware('auth');

Route::put('/profile', [UserController::class, 'update'])->middleware('auth');