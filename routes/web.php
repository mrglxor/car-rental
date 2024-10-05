<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('guest')->group(function () {
    Route::view('/auth', 'authenticate.auth')->name('auth');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [CustomerController::class, 'index'])->name('customer')->middleware('role:customer');

Route::post('/is-available', [CustomerController::class, 'isAvailable'])->name('isAvailable')->middleware('role:customer');
Route::post('/rent', [CustomerController::class, 'rent'])->name('rent')->middleware('role:customer');
Route::get('/sewa', [CustomerController::class, 'sewa'])->name('sewa')->middleware('role:customer');
Route::get('/rental', [CustomerController::class, 'rental'])->name('rental')->middleware('role:customer');
Route::get('/return', [CustomerController::class, 'rturn'])->name('return')->middleware('role:customer');
Route::post('/rent-return', [CustomerController::class, 'rentPost'])->name('rentPost')->middleware('role:customer');
Route::get('/owner-rental', [CustomerController::class, 'owner'])->name('owner')->middleware('role:customer');
Route::post('/ownerRental', [CustomerController::class, 'ownerRental'])->name('ownerRental')->middleware('role:customer');


Route::view('/staff', 'admin.dashboard')->name('staff')->middleware('role:staff');
Route::get('/renting', [AdminController::class, 'renting'])->name('renting')->middleware('role:staff');
Route::get('/renting-out', [AdminController::class, 'rentingOut'])->name('renting-out')->middleware('role:staff');


Route::view('/admin', 'admin.dashboard')->name('admin')->middleware('role:admin');
