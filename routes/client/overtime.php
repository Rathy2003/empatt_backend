<?php

use App\Http\Controllers\Client\RequestOvertimeController;
use Illuminate\Support\Facades\Route;

Route::post('request-overtime/store',[RequestOvertimeController::class,'store']);
