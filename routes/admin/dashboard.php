<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAuth;
use App\Http\Controllers\Admin\ProfileController;
use Inertia\Inertia;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware([CheckAuth::class])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

    Route::get('/qr-code',function(){
        return Inertia::render('Dashboard/QRCode/QRCode');
    })->name('qrcode');

    include 'company.php';
    include 'user.php';
    include 'qrcode.php';
});

