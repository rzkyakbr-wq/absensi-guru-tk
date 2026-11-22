<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;

// Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route Auth (Login, Logout, Register)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Register Guru
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Route Khusus Guru (Harus Login)
Route::middleware(['auth'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'guruDashboard'])->name('guru.dashboard');
    Route::post('/scan', [AttendanceController::class, 'processScan'])->name('guru.scan');
    Route::post('/update-photo', [AuthController::class, 'updateProfilePhoto'])->name('guru.updatePhoto');
});

// Route Khusus Admin (Harus Login)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/update-status/{userId}', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::get('/export-excel', [AdminController::class, 'exportExcel'])->name('admin.exportExcel');
});