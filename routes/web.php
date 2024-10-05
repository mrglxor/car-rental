<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('guest')->group(function () {
    Route::view('/auth', 'authenticate.auth')->name('auth');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::view('/dashboard', 'dashboard.main')->name('customer')->middleware('role:customer');

Route::view('/sewa', 'pages.sewa')->name('sewa');
Route::view('/rental', 'pages.rental')->name('rental');
Route::view('/owner-rental', 'pages.owner-rental')->name('owner-rental');
Route::view('/return', 'pages.return')->name('return');

Route::view('/admin/dashboard', 'admin.dashboard')->name('admin')->middleware('role:admin');
Route::view('/staff/dashboard', 'admin.dashboard')->name('staff')->middleware('role:staff');
