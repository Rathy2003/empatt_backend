<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CompanyController;

Route::group(['middleware' => ['role:developer']], function () {
    Route::get("/company",[CompanyController::class,'showCompany'])->name('company');
    Route::post("/company/create",[CompanyController::class,'save'])->name('createCompany');
    Route::post("/company/update",[CompanyController::class,'update'])->name('saveEditCompany');
    Route::post("/company/delete",[CompanyController::class,'delete'])->name('deleteCompany');
});



