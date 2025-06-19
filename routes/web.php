<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AuthController;

use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login',[AuthController::class,'showLoginForm'])->name('login');
Route::post('/login',[AuthController::class,'processLogin'])->name('processLogin');

include 'admin/authentication.php';
include 'admin/dashboard.php';
include 'admin/employee.php';
include 'admin/position.php';
include 'admin/qrcode.php';
include 'admin/request_leave.php';
include 'admin/overtime.php';


 Route::middleware('auth')->group(function () {
     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 });
