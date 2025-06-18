<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\RequestLeaveController;

Route::middleware(['auth', 'role:admin|ceo'])->group(function () {
    Route::get('/request-leave',[RequestLeaveController::class,'index'])->name('request-leave.index');
    Route::post('/request-leave/update-status',[RequestLeaveController::class,'updateStatus'])->name('request-leave.update-status');
});;
