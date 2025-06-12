<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ProfileController;

Route::post('/user/update', [ProfileController::class, 'update']);
