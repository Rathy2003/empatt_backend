<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::group(['middleware' => ['role:developer']], function () {
    Route::get("/user",[UserController::class,'showUser'])->name('user');
    Route::post("/user/create",[UserController::class,'save'])->name('createUser');
    Route::post("/user/update",[UserController::class,'update'])->name('saveUser');
    Route::post("/user/delete",[UserController::class,'delete'])->name('deleteUser');
});


Route::group(['middleware' => ['role:admin|ceo']], function () {
    Route::get('/account-information',[ProfileController::class,'showAccountInformation'])->name('accountInformation');
    Route::post('/account-information/save',[ProfileController::class,'saveInformation'])->name('saveInformation');
});

