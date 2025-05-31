<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get("/user",[UserController::class,'showUser'])->name('user');
Route::post("/user/create",[UserController::class,'save'])->name('createUser');
Route::post("/user/update",[UserController::class,'update'])->name('saveUser');
Route::post("/user/delete",[UserController::class,'delete'])->name('deleteUser');
