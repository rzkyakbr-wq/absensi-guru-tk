<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;

// Route untuk menyajikan file Storage Public (Foto Profil, dll)
Route::get('/storage/{path}', function ($path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('storage.file');

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

// Route Reset Password Mandiri (Guru)
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');

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
    Route::post('/reset-password/{userId}', [AdminController::class, 'resetGuruPassword'])->name('admin.resetGuruPassword');
    Route::get('/export-excel', [AdminController::class, 'exportExcel'])->name('admin.exportExcel');
    Route::delete('/delete-attendance/{attendanceId}', [AdminController::class, 'deleteAttendance'])->name('admin.deleteAttendance');
});