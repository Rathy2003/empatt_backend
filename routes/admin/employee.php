<?php

use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['role:admin']], function () { 
    Route::get('/employee',[EmployeeController::class,'index'])->name('employee.index');
    Route::post('/employee',[EmployeeController::class,'store'])->name('employee.store');
});









