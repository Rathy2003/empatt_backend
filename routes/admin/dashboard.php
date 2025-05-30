<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAuth;
use App\Http\Controllers\Admin\ProfileController;
use Inertia\Inertia;

Route::middleware([CheckAuth::class])->group(function () {
    Route::get('/dashboard',function(){
        return Inertia::render('Dashboard/Dashboard');
    })->name('dashboard');

    Route::get('/qr-code',function(){
        return Inertia::render('Dashboard/QRCode/QRCode');
    })->name('qrcode');

    include 'company.php';
    include 'user.php';

    Route::get('/account-information',[ProfileController::class,'showAccountInformation'])->name('accountInformation');
    Route::post('/account-information/save',[ProfileController::class,'saveInformation'])->name('saveInformation');

});
