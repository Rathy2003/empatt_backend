<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AuthController;



Route::post('/process-login', [AuthController::class, 'login']);
Route::post('/verify-token', [AuthController::class, 'verifyToken']);