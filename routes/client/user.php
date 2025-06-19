<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Admin\AuthController;

Route::post('/user/update', [ProfileController::class, 'update']);
Route::post('/user/resetPassword',[AuthController::class, 'sendResetCodeClient']);
Route::post('/user/verify-code',[AuthController::class, 'verifyResetCodeClient']);
Route::post('/user/change-password',[AuthController::class, 'processResetPasswordClient']);
