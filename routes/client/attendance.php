<?php

use App\Http\Controllers\Client\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::post("attendance/check",[AttendanceController::class,'check']);
