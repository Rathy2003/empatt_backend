<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

include 'client/authentication.php';
include 'client/user.php';
include 'client/attendance.php';
include 'client/request_leave.php';
include 'client/dashboard.php';
include 'client/overtime.php';
