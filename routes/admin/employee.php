<?php

use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/employee',[EmployeeController::class,'index'])->name('employee.index');
Route::post('/employee',[EmployeeController::class,'store'])->name('employee.store');







