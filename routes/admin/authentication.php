<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Middleware\CheckAuth;

Route::middleware([CheckAuth::class])->group(function () {
   Route::get('/logout',[AuthController::class,'processLogout'])->name('processLogout');

});
Route::post('/send-reset-code',[AuthController::class,'sendResetCode'])->name('sendResetCode');
Route::post('/verify-reset-code',[AuthController::class,'verifyResetCode'])->name('verifyResetCode');
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/process-reset-password', [AuthController::class, 'processResetPassword'])->name('processResetPassword');