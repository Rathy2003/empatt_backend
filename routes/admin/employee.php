<?php

use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin|ceo'])->group(function () { 
    Route::get('/employee',[EmployeeController::class,'index'])->name('employee.index');
    Route::post('/employee',[EmployeeController::class,'store'])->name('employee.store');
    Route::post('/employee/update',[EmployeeController::class,'update'])->name('employee.update');
    Route::post('/employee/delete',[EmployeeController::class,'destroy'])->name('employee.destroy');
});









