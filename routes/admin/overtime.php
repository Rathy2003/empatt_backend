<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RequestOvertimeController;

Route::middleware(['auth', 'role:admin|ceo'])->group(function () {
    Route::get('/overtime',[RequestOvertimeController::class,'index'])->name('overtime.index');
    Route::post('/overtime/update-status',[RequestOvertimeController::class,'updateStatus'])->name('overtime.update-status');
});;
