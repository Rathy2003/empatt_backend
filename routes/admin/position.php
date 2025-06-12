<?php

use App\Http\Controllers\Admin\PositionController;
use Illuminate\Support\Facades\Route;

Route::get('/position',[PositionController::class,'index'])->name('position.index');
Route::post('/position',[PositionController::class,'store'])->name('position.store');
Route::post('/position/update',[PositionController::class,'update'])->name('position.update');
Route::post('/position/delete',[PositionController::class,'destroy'])->name('position.delete');