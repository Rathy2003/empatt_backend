<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Middleware\CheckAuth;

Route::middleware([CheckAuth::class])->group(function () {
   Route::get('/logout',[AuthController::class,'processLogout'])->name('processLogout');
});
