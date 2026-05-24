<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Staff;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp']);
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.form');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/subjects', Admin\SubjectController::class);
    Route::resource('/users', Admin\UserController::class);
    Route::get('/reports', [Admin\DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [Admin\DashboardController::class, 'exportReport'])->name('reports.export');
});

Route::middleware(['auth', 'role:staff|admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');
    Route::put('/enrollments/{enrollment}/approve', [Staff\DashboardController::class, 'approve'])->name('enrollments.approve');
    Route::put('/enrollments/{enrollment}/reject', [Staff\DashboardController::class, 'reject'])->name('enrollments.reject');
    Route::post('/enrollments/bulk-approve', [Staff\DashboardController::class, 'bulkApprove'])->name('enrollments.bulk-approve');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/enroll', [Student\DashboardController::class, 'enroll'])->name('enroll');
    Route::get('/profile', [Student\DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [Student\DashboardController::class, 'updateProfile'])->name('profile.update');
});

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isStaff()) return redirect()->route('staff.dashboard');
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});
